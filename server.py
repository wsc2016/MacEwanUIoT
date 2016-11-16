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

HOST = '192.168.1.126'
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
    
def add_data(s_data, s_name, s_brand, s_type, l_name, h_desc, r_num):
    #open database connection
    db = MySQLdb.connect("localhost","root","root","iot_waste_management" )
    db.autocommit(False)
    
    #prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    #SQL queries to INSERT records into the database
    sql_1 = "INSERT INTO sensor_data(sensor_data) VALUES ('%d')" % (s_data)
    sql_2 = "INSERT INTO sensor_location(garbage_bin_location_name, hallway_description, room_number, sensor_id) VALUES ('%s', '%s', '%s', LAST_INSERT_ID())" % (l_name, h_desc, r_num)
    sql_3 = "INSERT INTO sensor_details(sensor_name, sensor_brand, sensor_type, sensor_id) VALUES ('%s', '%s', '%s', LAST_INSERT_ID())" % (s_name, s_brand, s_type)
    
    try:
        # Execute the SQL commands
        cursor.execute(sql_1)
        cursor.execute(sql_2)
        cursor.execute(sql_3)
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

def strip_data(text):
    array = text.split('['.encode())
    return array  

def assign_data(message):
    #strip segments from data 
    list = strip_data(message)
    SENSOR_DATA = int(list[0].decode())
    SENSOR_NAME = list[1].decode()
    SENSOR_BRAND = list[2].decode()
    SENSOR_TYPE = list[3].decode()
    BIN_LOCATION = list[4].decode()
    HALL_DESCRIPTION = list[5].decode()
    ROOM_NUMBER = list[6].decode()
    print ('\n')
    print ('Receiving:')
    print ('          SENSOR_READING: ', SENSOR_DATA)
    print ('          SENSOR_NAME: ', SENSOR_NAME)
    print ('          SENSOR_BRAND: ', SENSOR_BRAND)
    print ('          SENSOR_TYPE: ', SENSOR_TYPE)
    print ('          BIN_LOCATION: ', BIN_LOCATION)
    print ('          HALL_DESCRIPTION: ', HALL_DESCRIPTION)
    print ('          ROOM_NUMBER: ', ROOM_NUMBER)
    print ('\n')
            
    #send data to database
    success = add_data(SENSOR_DATA, SENSOR_NAME, SENSOR_BRAND, SENSOR_TYPE, BIN_LOCATION, HALL_DESCRIPTION, ROOM_NUMBER)
    return success

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
