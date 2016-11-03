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
##########################################################

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
KEY = '1234567890ABCDEF'
IV = '0987654321UVWXYZ'
BLOCK_SIZE = 16

SENSOR_NAME = 'MacEwan-10'
SENSOR_TYPE = "Sharp Infrared Proximity"
SENSOR_LOCATION = 'CCC Building 6, Second Floor'

def pad(s):
    return s + (BLOCK_SIZE - len(s) % BLOCK_SIZE) * chr(BLOCK_SIZE - len(s) % BLOCK_SIZE)

def encrypt(key, estring, iv):
    nstring = pad(estring)
    print "NSTRING: ", nstring
    cipher = AES.new(key, AES.MODE_CBC, iv)
    return base64.b64encode(cipher.encrypt(nstring))


def makestring(measurement, sname, stype, slocation, ):
    new_string =  measurement + '[' + sname + '[' + stype + '[' + slocation
    print "new_string: ", new_string
    return new_string


while True:
    time.sleep(0.5)         #Time delay between calls

    #Read 2 bytes of raw input from ADC
    var = bus.read_i2c_block_data(address, 12, 2) 

    #Perform 12-bit conversion
    voltage = (var[0] & 0x0F) * 256 + var[1]

    print "VOLTAGE: ", voltage

    try:
        s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)    #set up socket
        s.connect((HOST, PORT)) #connect to IP on PORT specified
        print 'Connecting to ', HOST, ' on port ', PORT

        #Send data
        send_string = makestring(str(voltage), SENSOR_NAME, SENSOR_TYPE, SENSOR_LOCATION)
        ciphertext = encrypt(KEY, send_string, IV)

        s.sendall(ciphertext + '\n')
        print 'Sent: ', str(voltage)

        #Wait for response
        data_recieved = 0
        data_expected = len(send_string)

        while data_recieved < data_expected:
            data = s.recv(1024)
            data_recieved += len(data)
            print 'Recieved: ', data
            print 'CIPHER_TEXT:', ciphertext
            print 'CIPHER_SIZE: ', len(ciphertext)



    finally:
        print "Data Transaction Completed"
        s.close()
    
