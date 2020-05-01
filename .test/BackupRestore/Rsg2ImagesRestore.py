#!/usr/bin/python

import os
import getopt
import sys
import shutil
import traceback

from datetime import datetime


HELP_MSG = """

yyy

Reads config from external file LangManager.ini
The segment selection tells which segment(s) to use for configuration

usage: _emptyPy.py -? nnn -? xxxx -? yyyy  [-h]
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
# _emptyPy
# ================================================================================

# ================================================================================
# config
# ================================================================================

class Rsg2ImagesRestore:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self,
                 backupPath = '../../../RSG2_Backup',
                 joomlaPath = 'd:/xampp/htdocs/joomla4x'):

        print("Init Rsg2ImagesRestore: ")
        print("backupPath: " + backupPath)
        print("joomlaPath: " + joomlaPath)

        self.__backupPath =          backupPath
        self.__joomlaPath =          joomlaPath

        # --- create path to image and dump file ----------------------------------

        self.__SrcImagePath = os.path.join(self.__backupPath, 'image')
        self.__DstImagePath = os.path.join(self.__joomlaPath, 'image')


    # --------------------------------------------------------------------
    # doCopy
    # --------------------------------------------------------------------

    def doCopy(self):

        try:
            print('*********************************************************')
            print('doCopy')
            print("src path: " + self.__SrcImagePath)
            print("dst path: " + self.__DstImagePath )
            print('---------------------------------------------------------')

            # --- copy -----------------------------------------------------------------

            shutil.copytree(self.__SrcImagePath, self.__DstImagePath )


        except Exception as ex:
            print('x Exception:' + ex)
            print(traceback.format_exc())

        # --------------------------------------------------------------------

        finally:
            print('exit doCopy')

        return

    ##-------------------------------------------------------------------------------

    def dummyFunction(self):

        print('    >>> Enter dummyFunction: ')


        # print ('       XXX: "' + XXX + '"')
        print('    >>> Exit dummyFunction: ')


# ================================================================================
# standard functions
# ================================================================================

def Wait4Key():
    try:
        input("Press enter to continue")
    except SyntaxError:
        pass


def testFile(file):
    exists = os.path.isfile(file)
    if not exists:
        print("Error: File does not exist: " + file)
    return exists


def testDir(directory):
    exists = os.path.isdir(directory)
    if not exists:
        print("Error: Directory does not exist: " + directory)
    return exists


def print_header(start):
    print('------------------------------------------')
    print('Command line:', end='')
    for s in sys.argv:
        print(s, end='')

    print('')
    print('Start time:   ' + start.ctime())
    print('------------------------------------------')


def print_end(start):
    now = datetime.today()
    print('')
    print('End time:               ' + now.ctime())
    difference = now - start
    print('Time of run:            ', difference)


# print ('Time of run in seconds: ', difference.total_seconds())

# ================================================================================
#   main (used from command line)
# ================================================================================

if __name__ == '__main__':

    start = datetime.today()

    optlist, args = getopt.getopt(sys.argv[1:], 'l:r:12345h')

    joomlaPath = 'd:/xampp/htdocs'
    # joomlaPath = 'e:/xampp/htdocs'
    # joomlaPath = 'f:/xampp/htdocs'
    # joomlaPath = 'e:/xampp_J2xJ3x/htdocs'
    # joomlaPath = 'f:/xampp_J2xJ3x/htdocs'

    # joomlaName = 'joomla4x'
    joomlaName = 'joomla3x'

    backupPath = '../../../RSG2_Backup'


    for i, j in optlist:
        if i == "-p":
            joomlaPath = j
        if i == "-n":
            joomlaName = j
        if i == "-b":
            backupPath = j

        if i == "-h":
            print(HELP_MSG)
            sys.exit(0)

        if i == "-1":
            LeaveOut_01 = True
            print("LeaveOut_01")
        if i == "-2":
            LeaveOut_02 = True
            print("LeaveOut__02")
        if i == "-3":
            LeaveOut_03 = True
            print("LeaveOut__03")
        if i == "-4":
            LeaveOut_04 = True
            print("LeaveOut__04")
        if i == "-5":
            LeaveOut_05 = True
            print("LeaveOut__05")

    print_header(start)

    joomlaPath = os.path.join(joomlaPath, joomlaName)

    rsg2ImagesRestore = Rsg2ImagesRestore(joomlaPath, backupPath)
    rsg2ImagesRestore.doCopy()

    print_end(start)

