##########################################################
# CMPT 496
# IoT Waste Management Project
#
# SDA/SCL Port Control Code
#
# Author: Walter Chelliah
# October/November 2016
##########################################################

#!!!    Requires more investigation as this currently disables
#!!!    and locks up I2C access when running

import RPi.GPIO as GPIO

def sensor_setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(2, GPIO.OUT)
    GPIO.setup(3, GPIO.OUT)

def ports_on():
    GPIO.output(2, 1)
    GPIO.output(3, 1)
    print ("PORTS ON")

def ports_off():
    GPIO.output(2, 0)
    GPIO.output(3, 0)
    print ("PORTS OFF")
