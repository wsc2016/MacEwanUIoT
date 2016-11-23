########################################################################################
# CMPT 496
# IoT Waste Management Project
#
# Python3
# Server-Side Code
# server.py
#
# Author: Walter Chelliah
# October/November 2016
#
# Description:
#       This is a server-side application that receives encrypted sensor data from a
#       client application. This application then decrypts the data and inserts it into a
#       mySQL database for storage and remote retrieval.
#
########################################################################################

from Crypto.Cipher import AES
import base64
import socket
import MySQLdb
import time

HOST = '192.168.56.101'
PORT = 4444
BLOCK_SIZE = 16

def pad(s):
    return s.decode() + (BLOCK_SIZE - len(s) % BLOCK_SIZE) * chr(BLOCK_SIZE - len(s) % BLOCK_SIZE)
    
def unpad(s):
    return s[:-ord(s[len(s)-1:])]

def encrypt(estring):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    nstring = pad(estring)
    cipher = AES.new(key, AES.MODE_CBC, iv)
    return base64.b64encode(cipher.encrypt(nstring))

def decrypt(data):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    cipher = AES.new(key, AES.MODE_CBC, iv)
    ndata = base64.b64decode(data)  
    return unpad(cipher.decrypt(ndata))
    
def add_data(s_did, s_data):
    #open database connection
    db = MySQLdb.connect("localhost","jharvard","crimson","iot_waste_management" )
    db.autocommit(False)
    
    #prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    #SQL queries to INSERT records into the database
    sql_1 = "INSERT INTO sensor_readings(sensor_details_id, sensor_reading) \
            VALUES ('%d', '%d')" % (s_did, s_data)
   
    try:
        # Execute the SQL commands
        cursor.execute(sql_1)
        
        # Commit changes in the database
        db.commit()
        data_status = "Data Successfully Added To Database"
    
    #MySQL error handling
    except MySQLdb.IntegrityError as e: 
        # handle a specific error condition
        print (repr(e))
    except MySQLdb.Error as e:
        # handle a generic error condition
        print (repr(e))
    except MySQLdb.Warning as e:
        # handle warnings, if the cursor you're using raises them
        print (repr(e))
    except:
        # Rollback in case there is any error
        db.rollback()
        data_status = "Data Not Added To Database"
        
    # disconnect from server and print success message
    db.close()
    return data_status

def get_data(sid, table):
    table_value = []    #initialize array for table row data
    
    #open database connection
    db = MySQLdb.connect("localhost","jharvard","crimson","iot_waste_management" )
    db.autocommit(False)
    
    #prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    #SQL queries to INSERT records into the database       
    if table == 0:
        sql = "SELECT sensor_name, sensor_brand, sensor_type, sensor_model \
        FROM sensor_details where sensor_details_id = '%d'" % (sid)
        
    if table == 1:
        sql = "SELECT garbage_bin_location_name, building_number, hallway_description, \
         room_number FROM sensor_location where sensor_location_id = '%d'" % (sid)
    
    try:
        # Execute the SQL commands
        cursor.execute(sql)
      
        # Fetch one row
        results = cursor.fetchone()

        #assign row to array
        table_value = [row for row in results]
    
    #MySQL error handling
    except MySQLdb.Error as e:
        # handle a generic error condition
        print('MySQLdb Error')
        print(repr(e))
        
    except MySQLdb.Warning as e:
        # handle warnings, if the cursor you're using raises them
        print('MySQLdb Warning')
        print (repr(e))
    
    except MySQLdb.DataError as e:
        print('MySQLdb DataError')
        print(repr(e))
 
    except MySQLdb.InternalError as e:
        print('MySQLdb InternalError')
        print(repr(e))
 
    except MySQLdb.IntegrityError as e:
        print('MySQLdb IntegrityError')
        print(repr(e))
 
    except MySQLdb.OperationalError as e:
        print('MySQLdb OperationalError')
        print(repr(e))
 
    except MySQLdb.NotSupportedError as e:
        print('MySQLdb NotSupportedError')
        print(repr(e))
 
    except MySQLdb.ProgrammingError as e:
        print('MySQLdb ProgrammingError')
        print(repr(e))

    except:
        # Rollback in case there is any error
        print ('Data Not Pulled From Database')
        
    # disconnect from server and print success message
    db.close()
    return table_value

def strip_data(text):
    array = text.split('['.encode())
    return array  

def assign_data(message):
    #strip segments from data 
    list = strip_data(message)
    
    #assign segments
    SENSOR_ID = int(list[0].decode())
    SENSOR_DATA = int(list[1].decode())

    print ('\n')
    print ('Receiving:')
    print ('          SENSOR_READING: ', str(SENSOR_DATA) + 'cm')
    print ('          SENSOR_ID: ', SENSOR_ID)
            
    #send sensor data to database
    success = add_data(SENSOR_ID, SENSOR_DATA)    
    
    #retrieve sensor data from database
    s_details(SENSOR_ID)
    s_location(SENSOR_ID)
    
    return success

def s_details(SENSOR_ID):
    #retrieve sensor data from sensor_details table
    table_details_array = get_data(SENSOR_ID,0)
    
    #assign array elements
    SENSOR_NAME = table_details_array[0]
    SENSOR_BRAND = table_details_array[1]
    SENSOR_TYPE = table_details_array[2]
    SENSOR_MODEL = table_details_array[3]
    
    print ('From:')
    print ('          SENSOR_NAME: ', SENSOR_NAME)
    print ('          SENSOR_BRAND: ', SENSOR_BRAND)
    print ('          SENSOR_TYPE: ', SENSOR_TYPE)
    print ('          SENSOR_MODEL: ', SENSOR_MODEL)
    
def s_location(SENSOR_ID):
    #retrieve sensor data from sensor_location table
    table_location_array = get_data(SENSOR_ID,1)
    
    #assign array elements
    BIN_LOCATION = table_location_array[0]
    BUILDING_NUMBER = table_location_array[1]
    HALL_DESCRIPTION = table_location_array[2]
    ROOM_NUMBER = table_location_array[3]
    
    print ('          BIN_LOCATION: ', BIN_LOCATION)
    print ('          BUILDING_NUMBER: ', BUILDING_NUMBER)
    print ('          HALL_DESCRIPTION: ', HALL_DESCRIPTION)
    print ('          ROOM_NUMBER: ', ROOM_NUMBER)
    print ('\n')

def recv_data():
    #create socket
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    print ('Socket created')

    #bind socket
    server_address = (HOST, PORT)
    s.bind(server_address)
    print ('Socket bound to ', HOST, ' on port ', PORT)

    #listen on socket
    s.listen(1)
    
    #counts input cycles
    cycle_count = 0

    while True:
        #waiting for connection
        print ('Waiting for incoming connection...')
    
        c, client_address = s.accept()
    
        try:
            print ('Connection from: ', client_address)
        
            #receive data
            data = c.recv(1024)
            message = decrypt(data)
        
            print (assign_data(message))
                  
            if data:
                cycle_count += 1
                print ('Sending Data Back To Client To Validate')
                c.sendall(encrypt(message))
                print ('Data Transaction Completed' + '\n')
                print ('===========================================================')
                print ('CYCLE ' + str(cycle_count) + ' COMPLETED')
                print ('===========================================================' + '\n')
            else:
                print ('No further data from ', client_address)
                break
                
        finally:
            #close connection
            c.close()

def main():
    recv_data()

if __name__ == "__main__":
    main()
