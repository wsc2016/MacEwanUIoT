##########################################################
# CMPT 496
# IoT Waste Management Project
#
# Python3
# Client-Side Code
# client.py
#
# Author: Walter Chelliah
# October/November 2016
#
# Mean Sensor Values:
#                       5cm:    3750
#                       25cm:   1469
#                       50cm:   950
#                       75cm:   830
#                       100cm:  698
#                       150cm:  575
#
# Description:
#       This is a client-side application that reads data
#       from a proximity sensor and sends it over a socket
#       to a remote server.  Additional features of this
#       application involve power managements function for
#       both the sensor and analog-to-digital convertor.
##########################################################

import RPi.GPIO as GPIO
from Crypto.Cipher import AES
from Crypto import Random
import base64

import smbus
import time
import socket

bus = smbus.SMBus(1)        #Sets I/O to I2C1
address = 0x48              #Use address found using "i2cdetect -y 1"
HOST = '192.168.1.126'
PORT = 4444
BLOCK_SIZE = 16

#Sensor Description
SENSOR_NAME = 'MacEwan-42'
SENSOR_BRAND = "Sharp"
SENSOR_TYPE = 'Infrared Proximity'
BIN_LOCATION = 'CCC Building 8, Third Floor'
HALL_DESCRIPTION = 'Gym Main Entrance'
ROOM_NUMBER = '8-330B'

# initialize sensor and ADC
def power_init():
    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(17, GPIO.OUT)
    GPIO.setup(18, GPIO.OUT)
    print ("POWER INITIALIZED")

# enable sensor
def sensor_on():
    GPIO.output(18, 1)
    print ("SENSOR ENABLED")

#disable sensor
def sensor_off():
    GPIO.output(18, 0)
    print ("SENSOR DISABLED")
    
#enable ADC
def power_up():
    GPIO.output(17, 1) #power up
    print ('POWERING UP ANALOG-DIGITAL CONVERTOR')
    time.sleep(1)
    #bus.write_byte(address, 0b00001100) #power up
    #print ('Powering UP Internal Reference')

#disable ADC
def power_down():
    GPIO.output(17, 0)  #power down
    print ('POWERING DOWN ANALOG-DIGITAL CONVERTOR')

def pad(s):
    return s + (BLOCK_SIZE - len(s) % BLOCK_SIZE) * chr(BLOCK_SIZE - len(s) % BLOCK_SIZE)

def unpad(s):
    return s[:-ord(s[len(s)-1:])]

def encrypt(estring):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    nstring = pad(estring)
    cipher = AES.new(key, AES.MODE_CBC, iv)
    return base64.b64encode(cipher.encrypt(nstring))

def decrypt(data):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    cipher = AES.new(key, AES.MODE_CBC, iv)
    ndata = base64.b64decode(data)  
    return unpad(cipher.decrypt(ndata))

def makestring(measurement):
    new_string =  measurement + '[' + SENSOR_NAME + '[' + SENSOR_BRAND + '[' + SENSOR_TYPE + '[' + BIN_LOCATION + '[' + HALL_DESCRIPTION + '[' + ROOM_NUMBER
    return new_string

def get_voltage():
    avg_voltage = 0

    #get twenty readings from sensor
    #first ten readings may include zeroes as sensor and ADC warms up so they are ignored
    for num in range(0,20):
        var = bus.read_i2c_block_data(address, 12, 2)   #Read 2 bytes of raw input from ADC
        voltage = (var[0] & 0x0F) * 256 + var[1]        #Perform 12-bit conversion
        time.sleep(0.2)

        #sum up the last ten readings
        if num >= 10:
            avg_voltage = avg_voltage + voltage

    #return the average of the last ten readings
    return str(int(avg_voltage/10))

def send_data():
    current = get_voltage()

    try:
        s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)    #set up socket
        s.connect((HOST, PORT)) #connect to IP on PORT specified
        print ('Connecting to ', HOST, ' on port ', PORT)
        print ('\n')

        #Send data
        send_string = makestring(current)
        ciphertext = encrypt(send_string)

        s.sendall(ciphertext + '\n'.encode('ascii'))
            
        print ('Sending:')
        print ('        SENSOR_READING: ', current)
        print ('        SENSOR_NAME: ', SENSOR_NAME)
        print ('        SENSOR_BRAND: ', SENSOR_BRAND)
        print ('        SENSOR_TYPE: ', SENSOR_TYPE)
        print ('        BIN_LOCATION: ', BIN_LOCATION)
        print ('        HALL_DESCRIPTION: ', HALL_DESCRIPTION)
        print ('        ROOM_NUMBER: ', ROOM_NUMBER)
        print ('\n')

        #Wait for response
        data_recieved = 0
        data_expected = len(send_string)

        while data_recieved < data_expected:
            data = decrypt(s.recv(1024))
            data_recieved += len(data.decode())

            if (data.decode() == send_string):   
                print ('Server Recieved OK')

    finally:
        print ('Data Transaction Completed' + '\n')
        print ('------------------------------------------------------------' + '\n')
        s.close()
    
def main():
    power_init()
    time.sleep(1)         #Time delay

    cycle_count = 0 #counts power cycles
    data_count = 0 #counts data retrieval cycles
    
    while True:
        power_up()
        time.sleep(1)         #Time delay
        sensor_on()
        print ('Acquiring Readings...')
        
        time.sleep(1)         #Time delay

        #sends one set of data to the server per power cycle
        send_data()
        
        sensor_off()
        time.sleep(0.5)         #Time delay
        power_down()

        cycle_count += 1
        print ('\n' + '============================================================')
        print ('CYCLE ' + str(cycle_count) + ' COMPLETED')
        print ('============================================================' + '\n')

        print ('Pausing...')
        time.sleep(5)         #Time delay

if __name__ == "__main__":
    main()
