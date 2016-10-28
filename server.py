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
import socket

HOST = 'localhost'
PORT = 4444

#create socket
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
print "Socket created."

#bind socket
server_address = ('192.168.1.126', PORT)
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
		while True:
			data = c.recv(16)
			obj = AES.new('1234567890ABCDEF', AES.MODE_CFB, '0987654321UVWXYZ')
			message = obj.decrypt(data)
			
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