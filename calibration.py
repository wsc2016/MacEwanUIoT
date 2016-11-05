##########################################################
# CMPT 496
# IoT Waste Management Project
#
# Sensor Calibration Code
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

import smbus
import time
import sys
import subprocess
import os

bus = smbus.SMBus(1)        #Sets I/O to I2C1
address = 0x48              #Use address found using "i2cdetect -y 1"
count = 1
sensorsum = 0

while True:
    time.sleep(0.01)         #Time delay between calls





    #Read 2 bytes of raw input from ADC
    var = bus.read_i2c_block_data(address, 12, 2)  # Find out the source of the reading

    #create graph using gnuplot
    

    #Perform 12-bit conversion
    voltage = (var[0] & 0x0F) * 256 + var[1]

    print var, voltage

    sensorsum = sensorsum + voltage
    mean = sensorsum / count

    count = count + 1


    if count == 1001 :

        print "MEAN: ", mean

        count = 0

        break
