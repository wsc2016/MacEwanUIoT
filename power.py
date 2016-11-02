
import time
import smbus

bus = smbus.SMBus(1)        #Sets I/O to I2C1
ADDRESS = 0x48              #Use address found using "i2cdetect -y 1"
count = 0

def power_up(count, address):
    if (count == 1):
        bus.write_byte(address, 0b00001100) #power up
        print 'Powering UP Internal Reference'
        print 'Powering UP Analog/Digital Convertor'
        time.sleep(0.05)
        
    var = bus.read_i2c_block_data(address, 12, 2)
    #Perform 12-bit conversion
    voltage = (var[0] & 0x0F) * 256 + var[1]
    return voltage
    

def power_down(address):
    bus.write_byte(address, 0b00000000)  #power down
    print 'Powering DOWN'
    


while True:
    time.sleep(0.5)         #Time delay between calls

    count = count + 1
    
    if (count < 3):
        print 'VOLTAGE: ', str(power_up(count, ADDRESS))
    else:
        power_down(ADDRESS)
        time.sleep(10)
        count = 0
        

    
        
    



    
    

    

