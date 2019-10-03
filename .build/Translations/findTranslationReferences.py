#!/usr/bin/python

import os
#import re
import getopt
import sys

from datetime import datetime
import re

HELP_MSG = """
findTranslationReferences looks into the files of given path and collects all 
finding of references to translations beginning with given lookup string like 
'COM_RSGALLERY2_'

usage: findTranslationReferences.py -? nnn -? xxxx -? yyyy  [-h]
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
# findTranslationReferences
# ================================================================================

def findTranslationReferences (StartPath, LookupString):
	try:
		print ('*********************************************************')
		print ('findTranslationReferences')
		print ('StartPath: ' + StartPath)
		print ('LookupString: ' + LookupString)
		print ('---------------------------------------------------------')
		
		#---------------------------------------------
		# check input
		#---------------------------------------------
		
		if StartPath == '' :
			print ('***************************************************')
			print ('!!! Source folder (StartPath) name is mandatory !!!')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(1)
			
			
		if not testDir(StartPath):
			print ('***************************************************')
			print ('!!! Source folder (StartPath) path not found !!! ? -l ' + StartPath + ' ?')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(2)
			
			
		#--------------------------------------------------------------------
		
		if LookupString == '' :
			print ('***************************************************')
			print ('!!! LookupString name is mandatory !!!')
			print ('***************************************************')
			print (HELP_MSG)
			Wait4Key ()
			sys.exit(3)
			
			
			
		#--------------------------------------------------------------------
		# Collect all *.php files
		#--------------------------------------------------------------------
		

		print ('startPath abs: ' + os.path.abspath(StartPath))

		fileList = []
		fileList = findFilesInFolder(os.path.abspath(StartPath), fileList, '.php', True)
		
		#print ('LookupString: ' + LookupString)
		#print ('---------------------------------------------------------')
		
		#--------------------------------------------------------------------
		# find all lookup + ... strings
		#--------------------------------------------------------------------
		
		#for fileName in fileList:
		#	print ('fileName: ' + fileName)
		
		#		return fileList
	
		#--------------------------------------------------------------------
		# 
		#--------------------------------------------------------------------
		
		idx = 0
		
		foundLines = []
		
		for fileName in fileList:
		#	print ('fileName: ' + fileName)
		
			with open(fileName, encoding="utf8") as fp:
				for cnt, line in enumerate(fp):
					if LookupString not in line:
						continue
						
					foundLines.append(line)
					
		#		for idx, reference in enumerate(foundReferences):
		#			print(idx + ': ' + reference)
	
		foundTranslations = {}
		for line in foundLines:
			print ('.', end='')
			x = re.search(r"\b" + LookupString + "\w+", line)
			# print(x.group())
			
			#foundTranslations.append(x.group())
			foundTranslations [x.group()] = 0
			
			
		#--------------------------------------------------------------------
		# 
		#--------------------------------------------------------------------
		
		
		
		#--------------------------------------------------------------------
		# copy Macro folder
		#--------------------------------------------------------------------
		
		
		
		#--------------------------------------------------------------------
		# Create 02 export install folder
		#--------------------------------------------------------------------
		
		
		
		#--------------------------------------------------------------------
		# Create 01 install folder
		#--------------------------------------------------------------------
		
		
		
		#--------------------------------------------------------------------
		# copy 7z Files
		#--------------------------------------------------------------------
		
		
		
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
		print ('exit findTranslationReferences')

	return foundTranslations

#-------------------------------------------------------------------------------
#
def yyy (XXX):
	print ('    >>> Enter yyy: ')
	print ('       XXX: "' + XXX + '"')
	
	ZZZ = ""
	
	try:
		pass

	except Exception as ex:
		print(ex)

	print ('    <<< Exit yyy: ' + ZZZ)
	return ZZZ

	
def findFilesInFolder(path, pathList, extension, subFolders = True):
    """  Recursive function to find all files of an extension type in a folder (and optionally in all subfolders too)

    path:        Base directory to find files
    pathList:    A list that stores all paths
    extension:   File extension to find
    subFolders:  Bool.  If True, find files in all subfolders under path. If False, only searches files in the specified folder
    """

    try:   # Trapping a OSError:  File permissions problem I believe
        for entry in os.scandir(path):
            if entry.is_file() and entry.path.endswith(extension):
                pathList.append(entry.path)
            elif entry.is_dir() and subFolders:   # if its a directory, then repeat process as a nested function
                pathList = findFilesInFolder(entry.path, pathList, extension, subFolders)
    except OSError:
        print('Cannot access ' + path +'. Probably a permissions error')

    return pathList

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
	optlist, args = getopt.getopt(sys.argv[1:], 'l:p:12345h')
	
	StartPath = '..\\..\\admin'
	StartPath = '..\\..\\site'
	LookupString = 'COM_RSGALLERY2_'
	
	for i, j in optlist:
		if i == "-p":
			StartPath = j
		if i == "-l":
			LookupString = j
			
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
	
	
	#print_header(start)
	
	foundReferences = findTranslationReferences (StartPath, LookupString)
	
	for idx, reference in enumerate(foundReferences):
		print (str(idx ) + ': ' + reference)
	
	#print_end(start)
	
