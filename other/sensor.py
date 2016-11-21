##########################################################
# CMPT 496
# IoT Waste Management Project
#
# Sensor Enable/Disable Code
#
# Author: Walter Chelliah
# October/November 2016
##########################################################

import RPi.GPIO as GPIO

def sensor_setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(18, GPIO.OUT)
    print ("SENSOR SETUP")

def sensor_on():
    GPIO.output(18, 1)
    print ("SENSOR ENABLED")

def sensor_off():
    GPIO.output(18, 0)
    print ("SENSOR DISABLED")
