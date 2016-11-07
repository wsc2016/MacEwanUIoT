##########################################################
# CMPT 496
# IoT Waste Management Project
#
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
HOST = '10.19.19.81'
PORT = 4444
BLOCK_SIZE = 16

#Sensor Description
SENSOR_NAME = 'MacEwan-10'
SENSOR_TYPE = 'Sharp Infrared Proximity'
SENSOR_LOCATION = 'CCC Building 6, Second Floor'

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
    print ('Powering UP Analog/Digital Convertor')
    time.sleep(1)
    bus.write_byte(address, 0b00001100) #power up
    print ('Powering UP Internal Reference')

#disable ADC
def power_down():
    GPIO.output(17, 0)  #power down
    print ('Powering DOWN Analog/Digital Convertor')

def pad(s):
    return s + (BLOCK_SIZE - len(s) % BLOCK_SIZE) * chr(BLOCK_SIZE - len(s) % BLOCK_SIZE)

def encrypt(estring):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    nstring = pad(estring)
    cipher = AES.new(key, AES.MODE_CBC, iv)
    return base64.b64encode(cipher.encrypt(nstring))

def makestring(measurement):
    new_string =  measurement + '[' + SENSOR_NAME + '[' + SENSOR_TYPE + '[' + SENSOR_LOCATION
    return new_string

def get_voltage():
    var = bus.read_i2c_block_data(address, 12, 2)   #Read 2 bytes of raw input from ADC
    voltage = (var[0] & 0x0F) * 256 + var[1]        #Perform 12-bit conversion
    return str(voltage)

def send_data():
    current = get_voltage()

    try:
        s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)    #set up socket
        s.connect((HOST, PORT)) #connect to IP on PORT specified
        print 'Connecting to ', HOST, ' on port ', PORT
        print '\n'

        #Send data
        send_string = makestring(current)
        ciphertext = encrypt(send_string)

        s.sendall(ciphertext + '\n')
            
        print 'Sending:'
        print '        SENSOR_READING: ', current
        print '        SENSOR_NAME: ', SENSOR_NAME
        print '        SENSOR_TYPE: ', SENSOR_TYPE
        print '        SENSOR_LOCATION: ', SENSOR_LOCATION
        print '\n'

        #Wait for response
        data_recieved = 0
        data_expected = len(send_string)

        while data_recieved < data_expected:
            data = s.recv(1024)
            data_recieved += len(data)

            if (data == send_string):   
                print 'Server Recieved OK'

    finally:
        print 'Data Transaction Completed' + '\n'
        print '------------------------------------------------------------' + '\n'
        s.close()
    
def main():
    power_init()
    time.sleep(1)         #Time delay

    cycle_count = 0 #counts power cycles
    
    while True:
        power_up()
        time.sleep(5)         #Time delay
        sensor_on()
        
        time.sleep(3)         #Time delay

        #sends three sets of data to the server per power cycle
        for num in range(0,10):
            send_data()
            time.sleep(0.5)
        
        sensor_off()
        time.sleep(0.5)         #Time delay
        power_down()

        cycle_count += 1
        print '\n' + '============================================================'
        print 'CYCLE ' + str(cycle_count) + ' COMPLETED'
        print '============================================================' + '\n'

        time.sleep(4)

if __name__ == "__main__":
    main()
