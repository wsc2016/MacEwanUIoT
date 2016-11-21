#########################################################
# CMPT 496
# IoT Waste Management Project
#
# ADC Power Management
#    -internal reference switch control
#    
# Author: Walter Chelliah
# October/November 2016
#########################################################

import smbus

bus = smbus.SMBus(1)        #Sets I/O to I2C1
address = 0x48              #Use address found using "i2cdetect -y 1"

#   internal reference POWER UP switch
#   - increases power draw by approximately 1V
#   - voltage range approaches 5V
def power_up():
    bus.write_byte(address, 0b00001100) #power up
    print ('Powering UP Internal Reference')
    print ('Powering UP Analog/Digital Convertor')

#   internal reference POWER DOWN switch
#   - decreases power draw by approximately 1V
#   - voltage range fluctuates from 3.3V to 4V
def power_down():
    bus.write_byte(address, 0b00000000)  #power down
    print ('Powering DOWN')
    

        

    
        
    



    
    

    

