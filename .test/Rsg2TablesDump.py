#!/usr/bin/python

import os
import getopt
import sys
import subprocess

from datetime import datetime

HELP_MSG = """
 dumps RSG2 tables from given database 

usage: Rsg2TablesDump.py d database -u user -p password -f dumpFileName -m mySqlPath -j isUseJ3xTables  [-h]
    -d database Name of database for dump
    -u user User name of database for dump
    -p password Password of database for dump
    -f dumpFileName destination file where the dump will be written into
    -m mySqlPath Path to the folder of the exe mysqldump.exe
    -j isUseJ3xTables dump also the old outdated J3x tables

	-h shows this message

	-1 <leave out> prepared for later use 
	-2 ..
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

# -------------------------------------------------------------------------------
LeaveOut_01 = False
LeaveOut_02 = False
LeaveOut_03 = False
LeaveOut_04 = False
LeaveOut_05 = False


# ================================================================================
# Rsg2TablesDump
# ================================================================================

class Rsg2TablesDump:
    """ dumps RSG2 tables from given database """

    def __init__(self,
                 database='joomla4x',
                 user='root',
                 password='',
                 dumpFileName='Rsg2_TablesDump.sql',
                 mySqlPath='',
                 isUseJ3xTables=True):

        self.__database = database
        self.__password = password
        self.__user = user
        self.__dumpFileName = dumpFileName
        self.__mySqlPath = mySqlPath
        self.__isUseJ3xTables = isUseJ3xTables

        #self.__rsg2Tables = ['', 'j4_rsg2_galleries', 'j4_rsg2_images', '']
        self.__rsg2Tables = ['j4_rsg2_galleries', 'j4_rsg2_images']
        self.__rsg2_j3x_Tables = ['j4_rsgallery2_acl', 'j4_rsgallery2_comments', 'j4_rsgallery2_config', 'j4_rsgallery2_files', 'j4_rsgallery2_galleries']

    #--- database ---

    @property
    def database(self):
        return self.__database

    @database.setter
    def database(self, database):
        self.__database = database

    #--- password ---

    @property
    def password(self):
        return self.__password

    @password.setter
    def password(self, password):
        self.__password = password

    #--- dumpFileName ---

    @property
    def dumpFileName(self):
        return self.__dumpFileName

    @dumpFileName.setter
    def dumpFileName(self, dumpFileName):
        self.__dumpFileName = dumpFileName

    #--- mySqlPath ---

    @property
    def mySqlPath(self):
        return self.__mySqlPath

    @mySqlPath.setter
    def mySqlPath(self, mySqlPath):
        self.__mySqlPath = mySqlPath

    #--- isWriteEmptyTranslations ---

    @property
    def isUseJ3xTables(self):
        return self.__isUseJ3xTables

    @isUseJ3xTables.setter
    def isUseJ3xTables(self, isUseJ3xTables):
        self.__isUseJ3xTables = isUseJ3xTables

    #--------------------------------------------------------------------
    #
    #--------------------------------------------------------------------
    # https://wiki.python.org/moin/ConfigParserExamples

    def doDumpTables (self, dumpFileName=''):
        # ToDo: Check if name exists otherwise standard
        # ToDo: try catch ...
        try:
            print('*********************************************************')
            print('doDumpTables')
            print('dumpFileName: ' + dumpFileName)
            print('---------------------------------------------------------')

            #--- save dump file name if given  -------------------------------
            
            if (dumpFileName == ''):
                dumpFileName = self.__dumpFileName 

            self.__dumpFileName = dumpFileName

            #--- check for mysqlDump exe ------------------------------

#            mySqlPath = "d:\\xampp\\mysql\\bin\\"
#            mySqlExe = self.__mySqlPath + "mysql.exe"
#            print("mySqlExe: " + mySqlExe)
#
#            if (not os.path.isfile(mySqlExe)):

            mySqlDump = mySqlPath + "mysqldump.exe"
            print("mySqlDump: " + mySqlDump)

            if (not os.path.isfile(mySqlDump)):
                msg = 'mysqldump.exe file did not exist: "' + mySqlDump
                print(msg)
                raise RuntimeError(msg) from os.error

            #---  database -------------------------------

            database = self.__database
            print("Dumping to %s" % database)

            #--- user  -------------------------------

            user = self.__user
            userCmd = '-u' + user            
            
            #--- password  -------------------------------

            password = self.__password

            passwordCmd = ' '
            if (password != ''):
                passwordCmd = '-p' + password            

            #--- tables command -------------------------------

            tablesCmd = ' '.join (self.__rsg2Tables)
#            if (self.__isUseJ3xTables):
#                tablesCmd +=  ' ' + ' '.join(self.__rsg2_j3x_Tables)

            #---  dump command -------------------------------

            dumpCmd = mySqlDump + ' ' + userCmd + ' ' + passwordCmd + ' ' + database  + ' ' + tablesCmd
            print("cmd: " + dumpCmd)

            #-------------------------------------------
            # do dump
            #-------------------------------------------

            with open(dumpFileName, 'w') as dumpFile:

                proc = subprocess.Popen(dumpCmd,
                                        stdout=dumpFile)
                proc.communicate()

#            dumpFile = open(dumpFileName, 'w')
#            proc = subprocess.Popen(dumpCmd,
#                                    stdout=dumpFile)
#            proc.communicate()

            dumpFile.close()

        except Exception as ex:
            print(ex)

        #--------------------------------------------------------------------
        #
        #--------------------------------------------------------------------

        finally:
            print('exit doDumpTables')

        return


##-------------------------------------------------------------------------------

        def dummyFunction():
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


def AddDateToFileName(fileName):

    DateTime = '{0:%Y%m%d_%H%M%S}'.format(datetime.now())

    parts = fileName.rsplit('.', 1)

    dateFileName = parts[0] + '.' + DateTime + '.' + parts[1]

    return dateFileName

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

    database = 'joomla4x'
    user = 'root'
    password = ''
    dumpFileName = 'Rsg2_TablesDump.sql'
    mySqlPath = 'd:\\xampp\\mysql\\bin\\'
    isUseJ3xTables = True

    for i, j in optlist:
        if i == "-d":
            database = j
        if i == "-p":
            password = j
        if i == "-u":
            user = j
        if i == "-f":
            dumpFileName = j
        if i == "-m":
            mySqlPath = j
        if i == "-j":
            isUseJ3xTables = j

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

    dumpFileName = AddDateToFileName (dumpFileName)
    Rsg2TablesDump = Rsg2TablesDump(database, user, password, dumpFileName, mySqlPath, isUseJ3xTables)

    Rsg2TablesDump.doDumpTables()

    print_end(start)

