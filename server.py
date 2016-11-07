########################################################################################
# CMPT 496
# IoT Waste Management Project
#
# Server-Side Code
# server.py
#
# Author: Walter Chelliah
# October/November 2016
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

def unpad(s):
    return s[:-ord(s[len(s)-1:])]
        
def decrypt(data):
    key = '1234567890ABCDEF'
    iv = '0987654321UVWXYZ'
    cipher = AES.new(key, AES.MODE_CBC, iv)
    ndata = base64.b64decode(data)  
    return unpad(cipher.decrypt(ndata))
    
def add_data(s_data, s_name, s_type, l_name):
    #open database connection
    db = MySQLdb.connect("localhost","root","root","iot_waste_management" )
    db.autocommit(False)
    
    #prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    #SQL queries to INSERT records into the database
    sql_1 = "INSERT INTO sensor_data(sensor_data, sensor_name, sensor_type) VALUES ('%d', '%s', '%s')" % (s_data, s_name, s_type)
    sql_2 = "INSERT INTO location(location_name, sensor_id) VALUES ('%s', LAST_INSERT_ID())" % (l_name)

    try:
        # Execute the SQL commands
        cursor.execute(sql_1)
        cursor.execute(sql_2)
        # Commit changes in the database
        db.commit()
        data_status = "Data Successfully Added To Database"
    
    #MySQL error handling
    except MySQLdb.IntegrityError, e: 
        # handle a specific error condition
        print repr(e)
    except MySQLdb.Error, e:
        # handle a generic error condition
        print repr(e)
    except MySQLdb.Warning, e:
        # handle warnings, if the cursor you're using raises them
        print repr(e)
    except:
        # Rollback in case there is any error
        db.rollback()
        data_status = "Data Not Added To Database"
        
    # disconnect from server and print success message
    db.close()
    return data_status

def strip_data(text):
    array = text.split('[')
    return array  

def assign_data(message):
    #strip segments from data 
    list = strip_data(message)
    SENSOR_DATA = int(list[0])
    SENSOR_NAME = list[1]
    SENSOR_TYPE = list[2]
    SENSOR_LOCATION = list[3]
    print '\n'
    print 'Receiving:'
    print '          SENSOR_READING: ', SENSOR_DATA
    print '          SENSOR_NAME: ', SENSOR_NAME
    print '          SENSOR_TYPE: ', SENSOR_TYPE
    print '          SENSOR_LOCATION: ', SENSOR_LOCATION
    print '\n'
            
    #send data to database
    success = add_data(SENSOR_DATA, SENSOR_NAME, SENSOR_TYPE, SENSOR_LOCATION)
    return success

def recv_data():
    #create socket
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    print "Socket created"

    #bind socket
    server_address = (HOST, PORT)
    s.bind(server_address)
    print "Socket bound to ", HOST, " on port ", PORT

    #listen on socket
    s.listen(1)
    
    #counts input cycles
    cycle_count = 0

    while True:
        #waiting for connection
        print "Waiting for incoming connection..."
    
        c, client_address = s.accept()
    
        try:
            print "Connection from: ", client_address
        
            #receive data
            data = c.recv(1024)
            message = decrypt(data)
        
            print assign_data(message)
                  
            if data:
                cycle_count += 1
                print "Sending Data Back To Client To Validate"
                c.sendall(message)
                print 'Data Transaction Completed' + '\n'
                print '==========================================================='
                print 'CYCLE ' + str(cycle_count) + ' COMPLETED'
                print '===========================================================' + '\n'
            else:
                print "No further data from ", client_address
                break
                
        finally:
            #close connection
            c.close()

def main():
    recv_data()

if __name__ == "__main__":
    main()
