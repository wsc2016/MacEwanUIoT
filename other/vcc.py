##########################################################
# CMPT 496
# IoT Waste Management Project
#
# ADC Enable/Disable Code
#
# Author: Walter Chelliah
# October/November 2016
#
# Description:
#       This code sets GPIO pin 17 as the VCC power source
#       for the ADC.  This allows for immediate power
#       on and power off of the ADC.
##########################################################

import RPi.GPIO as GPIO

def vcc_setup():
    GPIO.cleanup()
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(17, GPIO.OUT)
    print ("VCC SETUP")

def vcc_on():
    GPIO.output(17, 1)
    print ("VCC ENABLED")

def vcc_off():
    GPIO.output(17, 0)
    print ("VCC DISABLED")
