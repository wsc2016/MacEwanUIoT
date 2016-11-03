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
KEY = '1234567890ABCDEF'
IV = '0987654321UVWXYZ'
BLOCK_SIZE = 16
SENSOR_DATA = 3878
SENSOR_NAME = "ME00"
SENSOR_TYPE = "Sharp Infrared Proximity"
LOCATION_NAME = "CCC Building 4"




def unpad(s):
	return s[:-ord(s[len(s)-1:])]
        
def decrypt(key, data, iv):
	cipher = AES.new(key, AES.MODE_CBC, iv)
	ndata = base64.b64decode(data)
	print "NDATA: ", ndata   
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
        
    # disconnect from server and print success message
    db.close()
    data_added = "Data Successfully Added To Database"
    return data_added

def strip_data(text):
	array = text.split('[')
	#ADD SPLIT CLEANUP CODE HERE
	print "TEXT: ", text
	print "ARRAY: ", array
	print "SENSOR_DATA: ", array[0]
	print "SENSOR_NAME: ", array[1]
	print" SENSOR_TYPE: ", array[2]
	print" LOCATION_NAME: ", array[3]
	return array  



#create socket
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
print "Socket created."

#bind socket
server_address = (HOST, PORT)
s.bind(server_address)
print "Socket bound to ", HOST, " on port ", PORT

#listen on socket
s.listen(1)

while True:
    #waiting for connection
    print "Waiting for connection..."
    
    c, client_address = s.accept()
    
    try:
        print "Connection from: ", client_address
        
        #receive data
        data = c.recv(1024)
        print "RAW: ", data
        message = decrypt(KEY, data, IV)
        
        #strip segments from data 
        list = strip_data(message)
        SENSOR_DATA = int(list[0])
        SENSOR_NAME = list[1]
        SENSOR_TYPE = list[2]
        LOCATION_NAME = list[3]
            
        #send data to database
        success = add_data(SENSOR_DATA, SENSOR_NAME, SENSOR_TYPE, LOCATION_NAME)
        print success
        
        
        print "Received: ", message
            
        if data:
            print "Sending data back to client"
            c.sendall(message)
        else:
                print "No further data from ", client_address
                break
                
    finally:
        #close connection
        c.close()