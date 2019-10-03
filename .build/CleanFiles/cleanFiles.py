#!/usr/bin/python

import os
#import re
import getopt
import sys

from datetime import datetime

print (Echo "Prepared but not ready at all, (not even started)")

HELP_MSG = """
cleanFiles supports several tasks to clean up files

usage: cleanFiles.py -? nnn -? xxxx -? yyyy  [-h]
	-? nnn
	-? 

	
	-h shows this message
	
	-1 
	-2 
	-3 
	-4 
	-5 
	
	
	example:
	
	
------------------------------------
ToDo:
ToDo:
  * 
  * 
  * 
  * 
  * 
  
"""

#-------------------------------------------------------------------------------
LeaveOut_01 = False
LeaveOut_02 = False
LeaveOut_03 = False
LeaveOut_04 = False
LeaveOut_05 = False

#-------------------------------------------------------------------------------

# ================================================================================
# cleanFiles
# ================================================================================

def cleanFiles (LeftPath, RightPath):
	try:
		print ('*********************************************************')
		print ('cleanFiles')
		print ('LeftPath: ' + LeftPath)
		print ('RightPath: ' + RightPath)
		print ('---------------------------------------------------------')
		
		#---------------------------------------------
		# check input
		#---------------------------------------------
		
		if LeftPath == '' :
			print ('***************************************************')
			print ('!!! Source folder (LeftPath) name is mandatory !!!')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(1)
			
			
		if not testDir(LeftPath):
			print ('***************************************************')
			print ('!!! Source folder (LeftPath) path not found !!! ? -l ' + LeftPath + ' ?')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(2)
			
			
		#--------------------------------------------------------------------
		
		if RightPath == '' :
			print ('***************************************************')
			print ('!!! Destination folder (RightPath) name is mandatory !!!')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(1)
			
			
		if not testDir(RightPath):
			print ('***************************************************')
			print ('!!! Destination folder (RightPath) path not found !!! ? -r ' + RightPath + ' ?')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(2)
			
		#--------------------------------------------------------------------
		# ToDo: exchange left <-> right if left is not on N:
		#--------------------------------------------------------------------
		

		#print ('LeftPath: ' + LeftPath)
		#print ('RightPath: ' + RightPath)
		#print ('---------------------------------------------------------')
		
		#--------------------------------------------------------------------
		# determine build ID
		#--------------------------------------------------------------------
		
		ZZZ = determineZZZ (LeftPath)
		print ('ZZZ: ' + ZZZ)
		
		
		#--------------------------------------------------------------------
		# create base folder
		#--------------------------------------------------------------------
		
		installPath = os.path.join (RightPath, ZZZ)
		print ('installPath: ' + installPath)
		if not os.path.exists(installPath):
			os.makedirs(installPath)
		
		#--------------------------------------------------------------------
		# copy cexecuter folder
		#--------------------------------------------------------------------
		
		copyCexecuterFolder (LeftPath, installPath)
		
		#--------------------------------------------------------------------
		# copy Macro folder
		#--------------------------------------------------------------------
		
		copyMacroFolder (LeftPath, installPath)
		
		#--------------------------------------------------------------------
		# Create 02 export install folder
		#--------------------------------------------------------------------
		
		dstPath = os.path.join(installPath, '02.' + ZZZ + '_export')
		if not os.path.exists(dstPath):
			os.makedirs(dstPath)
		
		#--------------------------------------------------------------------
		# Create 01 install folder
		#--------------------------------------------------------------------
		
		dstPath = os.path.join(installPath, '01.' + ZZZ)
		if not os.path.exists(dstPath):
			os.makedirs(dstPath)
		
		#--------------------------------------------------------------------
		# copy 7z Files
		#--------------------------------------------------------------------
		
		copy7zFiles (LeftPath, installPath)
		
		#--------------------------------------------------------------------
		# 
		#--------------------------------------------------------------------
		
		
		
		#--------------------------------------------------------------------
		# 
		#--------------------------------------------------------------------
		
		
		#--------------------------------------------------------------------
		# 
		#--------------------------------------------------------------------
		
		
		
		
	finally:
		print ('exit cleanFiles')

#-------------------------------------------------------------------------------
#
def yyy (XXX):
	print ('    >>> Enter yyy: ')
	print ('       XXX: "' + XXX + '"')
	
	ZZZ = ""
	
	try:


	except Exception as ex:
		print(ex)

	print ('    <<< Exit yyy: ' + ZZZ)
	return ZZZ

##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#	
#	ZZZ = ""
#	
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ


##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#	
#	ZZZ = ""
#	
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ

##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#	
#	ZZZ = ""
#	
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ


#-------------------------------------------------------------------------------
# 
def CopyFolder	(src,dst):
	item_count = 0

	if os.path.isdir(src):

		for f in os.listdir(src):
			try:
				# not a useful file -> continue
				#if f == '.' or f == '..' or f == 'CVS' or f == '.git':
				#	continue

				item = os.path.join(src, f)
				if (os.path.isfile(item)):
					#print ('\tCopy "' + item + '" to "' + dst + '"')
										
					shutil.copy2(item, dst)
					item_count = item_count+1
				else:
					if (os.path.isdir(item)):
						nextDst = os.path.join(dst, f)
						
						# Create path if it does not exists
						if not os.path.exists(nextDst):
							os.makedirs(nextDst)
							
						CopyFolder (item, nextDst)
					
			except (OSError, IOError) as e:
				#say(e)
				print (e)
				#raise ...
				
				#pass
	else:
		print ('src: "' + src + '" is not a directory')
		return 0

	return item_count

##-------------------------------------------------------------------------------
	
def dummyFunction():
	print ('    >>> Enter dummyFunction: ')
	#print ('       XXX: "' + XXX + '"')
		

##-------------------------------------------------------------------------------

def Wait4Key():		
	try:
		input("Press enter to continue")
	except SyntaxError:
		pass		
			

def testFile(file):
	exists = os.path.isfile(file)
	if not exists:
		print ("Error: File does not exist: " + file)
	return exists

def testDir(directory):
	exists = os.path.isdir(directory)
	if not exists:
		print ("Error: Directory does not exist: " + directory)
	return exists

def print_header(start):

	print ('------------------------------------------')
	print ('Command line:', end='')
	for s in sys.argv:
		print (s, end='')
	
	print ('')
	print ('Start time:   ' + start.ctime())
	print ('------------------------------------------')

def print_end(start):
	now = datetime.today()
	print ('')
	print ('End time:               ' + now.ctime())
	difference = now-start
	print ('Time of run:            ', difference)
	#print ('Time of run in seconds: ', difference.total_seconds())

# ================================================================================
#   main (used from command line)
# ================================================================================
   
if __name__ == '__main__':
	optlist, args = getopt.getopt(sys.argv[1:], 'l:r:12345h')
	
	LeftPath = ''
	RightPath = ''
	
	for i, j in optlist:
		if i == "-l":
			LeftPath = j
		if i == "-r":
			RightPath = j
			
		if i == "-h":
			print (HELP_MSG)
			sys.exit(0)

		if i == "-1":
			LeaveOut_01 = True
			print ("LeaveOut_01")
		if i == "-2":
			LeaveOut_02 = True
			print ("LeaveOut__02")
		if i == "-3":
			LeaveOut_03 = True
			print ("LeaveOut__03")
		if i == "-4":
			LeaveOut_04 = True
			print ("LeaveOut__04")
		if i == "-5":
			LeaveOut_05 = True
			print ("LeaveOut__05")
	
	
	print_header(start)
	
	cleanFiles (LeftPath, RightPath)
	
	print_end(start)
	
