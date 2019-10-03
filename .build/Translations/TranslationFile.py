#!/usr/bin/python

"""
Collection of joomla translation strings  from one "Translation File"
"""

import os
#import re
import getopt
import sys

from datetime import datetime


HELP_MSG = """
TranslationFile supports ...

usage: TranslationFile.py -? nnn -? xxxx -? yyyy  [-h]
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
  * Read version and keep lang info
  * ? list of en-GB to english-United Kingdom assoc. for save
  * ? keep orig filename so it can be used on empty filename on save
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
# TranslationFile
# ================================================================================

class TranslationFile:

	""

	#---------------------------------------------
	def __init__ (self, translationFile=''):
		print( "Init TranslationFile: ")
		print ("translationFile: " + translationFile)
		self.translationFile = translationFile
#		self.LocalPath = LocalPath
		self.translations = {}
		self.doubles = {}

		if (os.path.isfile(translationFile)):
			self.load ()


	def load (self, fileName=''):
		try:
			print ('*********************************************************')
			print ('load')
			print ('fileName: ' + fileName)

			print ('---------------------------------------------------------')

			self.translations = {}
			self.doubles = {}

			#---------------------------------------------
			# Read file
			#---------------------------------------------

			if fileName == '' :
				fileName = self.translationFile

			if (os.path.isfile(fileName)):
				print ('Found fileName: ' + fileName)
				#print ('fileName: ' + fileName)

				with open(fileName, encoding="utf8") as fp:
					for cnt, line in enumerate(fp):
						#if LookupString not in line:
						#	continue
						line = line.strip()

						idx = line.find ('=')

						#if '=' not in line:
						if (idx < 0):
							continue
						
						# comment
						if (line[0] == ';'):
							continue

						transId = line[:idx].strip ()

						transText = line[idx+1:].strip ()
						#print ('transText (1): ' + transText)
						# Remove ""
						transText = transText [1:-1]
						#print ('transText (2): ' + transText)
						
						# prepared lines in file : com... = ""
						if (len(transText) < 1):
							continue


						# Key does already exist
						if (transId in self.translations):
							# Save last info
							self.doubles [transId] = self.translations [transId]

						self.translations [transId] = transText



			return


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
			print ('exit TranslationFile')

	def save (self, fileName='', isTest=False):
		try:
			print ('*********************************************************')
			print ('save')
			
			# use class filename
			if (fileName == ''):
				fileName = self.translationFile
				
			print ('fileName: ' + fileName)
			
			isTest = True # ToDo: remove later
			print ('isTest: ' + str(isTest))

			print ('---------------------------------------------------------')


			#--------------------------------------------------------------------
			# open file
			#--------------------------------------------------------------------

			# Do test output only
			if (isTest):
				useFileName = fileName + ".new"
			else:
				useFileName = fileName

			# todo: check for no bom 
			with open(useFileName, mode="w", encoding="utf8") as fh:

				#--------------------------------------------------------------------
				# write header
				#--------------------------------------------------------------------
	
				"""
				; en-GB (english-United Kingdom) language file for RSGallery2
				; @version $Id: en-GB.com_rsgallery2.ini 1090 2012-07-09 18:52:20Z mirjam $
				; @package RSGallery2
				; @copyright (C) 2003-2018 RSGallery2 Team
				; @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
				; @author RSGallery2 Team
				;
				; Last updated: used en-GB.com_rsgallery2.ini from SVN 1078, translated till SVN 1079
				; Save in UTF-8 without BOM (with e.g. Notepad ++)
	
				; If the language file only shows the keys in Joomla, turn on Joomla's debug system and
				; debug language (global configuration) and check for 'Parsing errors in language files'.
				; This will also show a list of 'Untranslated Strings'.
	
				; ToDo: Prevent on install writing *.ini file into \administrator\language\ and delete existing translations there
				"""
				
#				datetime.datetime.now().strftime("%Y-%m-%d %H:%M")
				
				baseName = os.path.basename(fileName)
				dateFormat = datetime.now().strftime("%Y-%m-%d")
				dateYear = datetime.now().strftime("%Y")
				
				HeaderTxt = ''
				#HeaderTxt += "; " + baseName[:5] + ' (' + baseName + ')  language file for RSGallery2 ' + u'\n'
				HeaderTxt += "; " + baseName + '  language file for RSGallery2 ' + u'\n'
				HeaderTxt += "; " + '@version ' + dateFormat + u'\n'
				HeaderTxt += "; " + '@package RSGallery2 ' + u'\n'
				HeaderTxt += "; " + '@copyright (C) 2003-' + dateYear + ' RSGallery2 Team ' + u'\n'
				HeaderTxt += "; " + '@license http://www.gnu.org/copyleft/gpl.html GNU/GPL ' + u'\n'
				HeaderTxt += "; " + '@author RSGallery2 Team ' + u'\n'
	
				fh.write (HeaderTxt)
	
				#--------------------------------------------------------------------
				# write all lines
				#--------------------------------------------------------------------
	
				idx = 0
				
				TranslLines = ''
				
				print ("Translations: " + str(len (self.translations)))
				
				#for key, value in self.translations.items():
				for key in sorted(self.translations.keys()):
					
					value = self.translations [key]
					
					# separator each 5 lines
					if (idx % 5 == 0):
						TranslLines += "" + ' ' + u'\n'

					# mark each 50 lines
					if (idx % 50 == 0):
						TranslLines += "; ------------------------------------------" + u'\n'
					
					idx += 1
					print (idx, end=', ')
				
					#print ("   " + key + " = " + value)
					TranslLines += key + ' = '  + value + u'\n'
				
				TranslLines += "" + ' ' + u'\n'
				TranslLines += "" + ' ' + u'\n'
				TranslLines += "" + ' ' + u'\n'
				
				fh.write(TranslLines)
		
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
			print ('exit save')

	#-------------------------------------------------------------------------------
	# ToDo: Return string instead of print
	def Text (self):
		#print ('    >>> Enter yyy: ')
		#print ('       XXX: "' + XXX + '"')

		ZZZ = ""

		try:
			print ("Translations: " + str(len (self.translations)))
			for key, value in self.translations.items():
				print ("   " + key + " = " + value)

			print ("Doubles: " + str(len (self.doubles)))
			for key, value in self.doubles.items():
				print ("   " + key + " = " + value)

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
	optlist, args = getopt.getopt(sys.argv[1:], 'l:r:12345h')

	langFile = '..\\..\\admin\language\en-GB\en-GB.com_rsgallery2.ini'


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


	#print_header(start)

	TransFile = TranslationFile (langFile)

	TransFile.Text ()
	#print_end(start)
	
	TransFile.save ('', True) # save on new name


