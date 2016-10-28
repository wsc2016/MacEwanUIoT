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

import smbus
import time
import socket

bus = smbus.SMBus(1)        #Sets I/O to I2C1
address = 0x48              #Use address found using "i2cdetect -y 1"
HOST = '192.168.1.126'
PORT = 4444

while True:
    time.sleep(0.5)         #Time delay between calls

    #Read 2 bytes of raw input from ADC
    var = bus.read_i2c_block_data(address, 12, 2) 

    #Perform 12-bit conversion
    voltage = (var[0] & 0x0F) * 256 + var[1]

    try:
        s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)    #set up socket
        s.connect((HOST, PORT)) #connect to IP on PORT specified
        print 'Connecting to ', HOST, ' on port ', PORT

        #Send voltage
        obj = AES.new('1234567890ABCDEF', AES.MODE_CFB, '0987654321UVWXYZ')
        ciphertext = obj.encrypt(str(voltage))
        s.sendall(ciphertext + '\n')
        print 'Sent: ', ciphertext

        #Wait for response
        data_recieved = 0
        data_expected = len(str(voltage))

        while data_recieved < data_expected:
            data = s.recv(16)
            data_recieved += len(data)
            print 'Recieved: ', data

    finally:
        print "Data Transaction Completed"
        s.close()
