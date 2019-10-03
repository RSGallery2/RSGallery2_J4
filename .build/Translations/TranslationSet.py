#!/usr/bin/python

"""
Collection of joomla translation files (translation lines) from one translation type
"""

import os
#import re
import getopt
import sys

from datetime import datetime

from TranslationFile import TranslationFile

HELP_MSG = """
TranslationSet supports ...
Collection of files with translation types. 
The files will be loadesd in given directory
The set

usage: TranslationSet.py -? nnn -? xxxx -? yyyy  [-h]
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
# TranslationSet
# ================================================================================

class TranslationSet:

	""

	#---------------------------------------------
	def __init__ (self, langDirectory='', langType=''):
		print( "Init TranslationSet: ")
		print ("langDirectory: " + langDirectory)
		print ("langType: " + langType)

		# ToDo: same init in translation file
		# parameter given, init inn load
		if (langDirectory != '' and langType != ''):

			self.load (langDirectory, langType)

		else:
			self.transFiles = []

			if (langDirectory != ''):
				self.langDirectory = langDirectory
	
			if (langType != ''):
				self.langType = langType
	


	# find all type matching files in directory
	def load (self, langDirectory='', langType=''):
		
		#return
		
		try:
			print ('*********************************************************')
			print ('load')
			print("langDirectory: " + langDirectory)
			print("langType: " + langType)
			
			print ('---------------------------------------------------------')

			self.transFiles = []
			
			if (langDirectory == '' or langType == ''):
				print ('!!! Missing information. Can not search for language files !!!')
				return
			
			#---------------------------------------------
			# Find files of type
			#---------------------------------------------

			fileQuery = '*.' + langType
			
			startDir = langDirectory
			transFileNames = self.findFilesOfType (startDir, langType)
			
			print ()
			print ('translation files found: ' + len (transFileNames))
			
			# --------------------------------------------------------------------
			# create TransFile objects
			# --------------------------------------------------------------------
			
			# self.transFiles = []
			for transFileName in transFileNames:
				transFile = TranslationFile(transFileName)
				self.transFiles.append(transFile)


		finally:
			print ('exit TranslationSet')
	
	def findFilesOfType(self, actDir, langType):
		
		foundFiles = []
		
		try:
			print('---------------------------------------------------------')
			print('findFilesOfType')
			print("actDir: " + actDir)
			print("actDir: " + os.path.abspath(actDir))
			print("langType: " + langType)
			
			print('---------------------------------------------------------')
		
			print ('*', end='')
			
			
			# if directory exist
			if os.path.isdir(actDir):
			
				# --------------------------------------------------------------------
				# All files or dir in actual directory
				# --------------------------------------------------------------------
				
				for name in os.listdir(actDir):
					
					filePathName = os.path.join(actDir, name)
					
					# file found ?
					if os.path.isfile(filePathName):
						# --------------------------------------------------------------------
						#  found type at end of name ?
						# --------------------------------------------------------------------
						
						if name.endswith(langType):
							foundFiles.append(filePathName)
				
					else:
						# --------------------------------------------------------------------
						# check files in sub directory
						# --------------------------------------------------------------------
						
						if os.path.isdir(filePathName):
							
							subFileNames = self.findFilesOfType(filePathName, langType)
							foundFiles.extend(subFileNames)
			
		finally:
			print('exit findFilesOfType')
			pass
		
		# --------------------------------------------------------------------
		# return found files
		# --------------------------------------------------------------------
		
		return foundFiles

	def save (self, isTest=False):
		
		try:
			print ('*********************************************************')
			print ('save')
			
			# use class filename
			isTest = True # ToDo: remove later
			print ('isTest: ' + str(isTest))

			print ('---------------------------------------------------------')
			
			# --------------------------------------------------------------------
			# Save all translations to files
			# --------------------------------------------------------------------
			
			for transFile in self.transFiles:
				transFile.save ('', isTest)

			#--------------------------------------------------------------------
			#
			#--------------------------------------------------------------------

		finally:
			print ('exit save')

	#-------------------------------------------------------------------------------
	# ToDo: Return string instead of print
	def Text (self, verbose=False):

		#print ('    >>> Enter yyy: ')
		#print ('       XXX: "' + XXX + '"')

		ZZZ = ""
		try:
			print ("Translation sets: " + str(len (self.translations)))
			for transFile in self.transFiles:
				print ("   >>> " + transFile.translationFile)
			
				# --------------------------------------------------------------------
				# extended information
				# --------------------------------------------------------------------

				if (verbose):
					for transFile in self.transFiles:
						transFile.print ()
				
		except Exception as ex:
			print(ex)

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
	optlist, args = getopt.getopt(sys.argv[1:], 'd:t:12345h')

	langDirectory= '..\\admin\language'
	langType= 'ini'
	#langType= 'sys.ini'


	for i, j in optlist:
		if i == "-d":
			langDirectory = j
		if i == "-t":
			langType = j

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

	TransSet01 = TranslationSet (langDirectory, langType)
	
	TransSet01.Text ()
	# TransSet01.Text (True)
	#print_end(start)
	
	TransSet01.save ('', True) # save on new name


