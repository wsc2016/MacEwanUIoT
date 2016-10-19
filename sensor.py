##########################################################
# CMPT 496
# IoT Waste Management Project
#
# Sensor Access Code
#
# Author: Walter Chelliah
# October/November 2016
##########################################################

import smbus
import time
import sys
import subprocess
import os

bus = smbus.SMBus(1)        #Sets I/O to I2C1
address = 0x48              #Use address found using "i2cdetect -y 1"
count = 0

while True:
    time.sleep(0.1)         #Time delay between calls

    #METHOD 1 - Returns 16 values (2-15)
    #========================================
    #for i in range(0,16):   #Runs through range of addresses 0x00 - 0x0f
                            #Found using "i2cget -y 1 0x48"
        
    #    print bus.read_byte_data(address, i),       #WORKING - PRIMARY METHOD   
    #print bus.read_word_data(address, 0)      #WORKING
    #print bus.read_block_data(address, 0)     #WORKING
    #print bus.read_i2c_block_data(address, 0) #WORKING
    #print bus.read_byte(address)                   #WORKING

    #sys.stdout.write("\n")



    #METHOD 2 - Returns 2 values (0-15, 0-255) - EXHIBITS VALUE CREEP
    #=========================================
    #Read 2 bytes of raw input from ADC
    var = bus.read_i2c_block_data(address, 0, 2)

    #Perform 12-bit conversion
    voltage = (var[0] & 0x0F) * 256 + var[1]

    print var, voltage

    count = count + 1

    #Runs i2cget in the background to prevent value creep
    if count == 500 :
        FNULL = open(os.devnull, 'w')
        hidden = subprocess.call(['i2cget', '-y', '1', str(address), '0x0f'],
                             stdout = FNULL, stderr = subprocess.STDOUT, close_fds = True)
        count = 0
