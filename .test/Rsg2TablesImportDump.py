#!/usr/bin/python

import os
import getopt
import sys
import subprocess

from datetime import datetime

HELP_MSG = """
Imports dump of RSG2 tables from given database 

usage: Rsg2TablesImportDump.py d database -u user -p password -f dumpFileName -m mySqlPath -j isUseJ3xTables  [-h]
    -d database Name of database for dump
    -u user User name of database for dump
    -p password Password of database for dump
    -f dumpFileName source file of the dump 
    -m mySqlPath Path to the folder of the exe mysql.exe

	-h shows this message

	-1 <leave out> prepared for later use 
	-2 ..
	-3 
	-4 
	-5 


	example:


------------------------------------
ToDo:
  * user configparser for init of class https://wiki.python.org/moin/ConfigParserExamples
  * doImportDumpTables -> add database parameter
  * create ..\..\data for exchange of sql ...
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
# Rsg2TablesImportDump
# ================================================================================

class Rsg2TablesImportDump:
    """ dumps RSG2 tables from given database """

    def __init__(self,
                 database='test',  # 'joomla4x',
                 user='root',
                 password='',
                 dumpFileName='Rsg2_TablesDump.20200414_215456.sql',  # 'Rsg2_TablesDump',
                 mySqlPath='d:\\xampp\\mysql\\bin\\'):

        self.__database = database
        self.__password = password
        self.__user = user
        self.__dumpFileName = dumpFileName
        self.__mySqlPath = mySqlPath


    # --- database ---

    @property
    def database(self):
        return self.__database

    @database.setter
    def database(self, database):
        self.__database = database

    #--- user ---

    @property
    def user(self):
        return self.__user

    @user.setter
    def user(self, user):
        self.__user = user

    # --- password ---

    @property
    def password(self):
        return self.__password

    @password.setter
    def password(self, password):
        self.__password = password

    # --- dumpFileName ---

    @property
    def dumpFileName(self):
        return self.__dumpFileName

    @dumpFileName.setter
    def dumpFileName(self, dumpFileName):
        self.__dumpFileName = dumpFileName

    # --- mySqlPath ---

    @property
    def mySqlPath(self):
        return self.__mySqlPath

    @mySqlPath.setter
    def mySqlPath(self, mySqlPath):
        self.__mySqlPath = mySqlPath

    # --- isWriteEmptyTranslations ---

    @property
    def isUseJ3xTables(self):
        return self.__isUseJ3xTables

    @isUseJ3xTables.setter
    def isUseJ3xTables(self, isUseJ3xTables):
        self.__isUseJ3xTables = isUseJ3xTables

    # --------------------------------------------------------------------
    # do import dump tables
    # --------------------------------------------------------------------

    def doImportDumpTables(self, dumpFileName='', dumpDatabase=''):
        # ToDo: Check if name exists otherwise standard
        # ToDo: try catch ...
        try:
            print('*********************************************************')
            print('doImportDumpTables')
            print('dumpFileName (in): ' + dumpFileName)
            print('---------------------------------------------------------')

            # --- save dump file name if given  -------------------------------

            if (dumpFileName == ''):
                dumpFileName = self.__dumpFileName

            self.__dumpFileName = dumpFileName

            print('dumpFileName (used): ' + self.__dumpFileName)

            # --- save dump dumpDatabase name if given  -------------------------------

            if (dumpDatabase == ''):
                dumpDatabase = self.__dumpDatabase

            self.__dumpDatabase = dumpDatabase

            print('dumpDatabase (used): ' + self.__dumpDatabase)

            # --- read dump file sql data -------------------------------

            print("Read %s" % self.__dumpFileName)

            # display first lines (ToDo: may be commented later )
            with open(self.__dumpFileName, encoding="utf-8") as sqlFile:
                sqlLines = sqlFile.read()

                lines = sqlLines.split('\n', 20)
                # for line in lines:
                for index, line in enumerate(lines):
                    print(index + 1, line)
                    if index > 10:
                        break

                lines = ""

            # read sql content as byte
            with open(self.__dumpFileName, 'rb') as sqlFile:
                sqlBytes = sqlFile.read()

            # --- check for mysql exe ------------------------------

            mySqlExe = mySqlPath + "mysql.exe"
            print("mySqlDump: " + mySqlExe)

            if (not os.path.isfile(mySqlExe)):
                msg = 'mysql.exe file did not exist: "' + mySqlExe
                print(msg)
                raise RuntimeError(msg) from os.error

            # ---  database -------------------------------

            database = self.__database
            print("Inserting into %s" % database)

            # --- user  -------------------------------

            user = self.__user
            userCmd = '-u' + user

            # --- password  -------------------------------

            password = self.__password

            passwordCmd = ' '
            if (password != ''):
                passwordCmd = '-p' + password


            # --- import dump command -------------------------------

            insertCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database
            print("cmd: " + insertCmd)

            # -------------------------------------------
            # do import dump
            # -------------------------------------------

            proc = subprocess.Popen(insertCmd, shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            proc.stdin.write(sqlBytes)
            proc.communicate()[0]
            proc.stdin.close()

        except Exception as ex:
            print(ex)

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit doImportDumpTables')

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

    database = 'test'  # 'joomla4x'
    user = 'root'
    password = ''
    dumpFileName = 'Rsg2_TablesDump.20200414_215456.sql' # 'Rsg2_TablesDump'
    mySqlPath = 'd:\\xampp\\mysql\\bin\\'

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

    Rsg2TablesImportDump = Rsg2TablesImportDump(database, user, password, dumpFileName, mySqlPath)

    Rsg2TablesImportDump.doImportDumpTables()

    print_end(start)

