#!/usr/bin/python

import os
import getopt
import sys
import subprocess
import traceback

from datetime import datetime

HELP_MSG = """
Exctracts rsgallery2 table names from given database
Finds j3x and j4x versions and adds them in common array

Does also retrieve the table prefix 

usage: jRsg2Tables.py -d database -u user -p password -m mySqlPath  [-h]
    -d database Name 
    -u user User name of database for dump
    -p password Password of database for dump
    -m mySqlPath Path to the folder of the exe mysql.exe
	
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

class jRsg2Tables:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self,
                 database='joomla4x',
                 user='root',
                 password='',
                 mySqlPath='d:\\xampp\\mysql\\bin\\'):

        print("Init jRsg2Tables: ")

        self.__database = database
        self.__password = password
        self.__user = user
        self.__mySqlPath = mySqlPath

        self.__tableNames = []
        self.__tableNames_j4x = []
        self.__tableNames_j3x = []

        self.__dbPrefix = 'unknown_'

        # ---------------------------------------------
        # assign variables from config file
        # ---------------------------------------------

        self.readTableNames ()

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

    # --- mySqlPath ---

    @property
    def mySqlPath(self):
        return self.__mySqlPath

    @mySqlPath.setter
    def mySqlPath(self, mySqlPath):
        self.__mySqlPath = mySqlPath

    # --- tables ---

    @property
    def tables(self):
        return self.__tableNames

    @tables.setter
    def tables(self, tables):
        self.__tableNames = tables

    # --- tables_j4x ---

    @property
    def tables_j4x(self):
        return self.__tableNames_j4x

    @tables_j4x.setter
    def tables_j4x(self, tables_j4x):
        self.__tableNames_j4x = tables_j4x

    # --- tables_j3x ---

    @property
    def tables_j3x(self):
        return self.__tableNames_j3x

    @tables_j3x.setter
    def tables_j3x(self, tables_j3x):
        self.__tableNames_j3x = tables_j3x

    # --- dbPrefix ---

    @property
    def dbPrefix(self):
        return self.__dbPrefix

    @dbPrefix.setter
    def dbPrefix(self, dbPrefix):
        self.__dbPrefix = dbPrefix


    # --- hasTables ---

    @property
    def hasTables(self):
        return len (self.__tableNames) > 0

    # --- hasJ4xTables ---

    @property
    def hasJ4xTables(self):
        return len (self.__tableNames_j4x) > 0

    # --- hasJ3xTables ---

    @property
    def hasJ3xTables(self):
        return len (self.__tableNames_j3x) > 0

    # --------------------------------------------------------------------
    # readTableNames
    # --------------------------------------------------------------------
    # https://wiki.python.org/moin/ConfigParserExamples

    def readTableNames (self,
                        database='',
                        user='',
                        password='NOT_USED_AT_ALL'):

        try:
            print('*********************************************************')
            print('readTableNames')
            print('database: ' + database)
            print('---------------------------------------------------------')

            # --- save inputs if given  -------------------------------

            if (database != ''):
                self.__database = database
            print('database (used): ' + self.__database)

            if (user != ''):
                self.__user = user

            if (password != 'NOT_USED_AT_ALL'):
                self.__password = password

            #--- init Lists -------------------------------

            self.__tableNames = []
            self.__tableNames_j4x = []
            self.__tableNames_j3x = []

            self.__dbPrefix = 'unknown_'

            #------------------------------------------------------------------
            # Read table names from database
            #------------------------------------------------------------------

            # --- check for mysql exe ------------------------------

            mySqlExe = os.path.join(self.__mySqlPath, "mysql.exe")
            print("mySqlExe: " + mySqlExe)

            if (not os.path.isfile(mySqlExe)):
                msg = 'mysql.exe file did not exist: "' + mySqlExe
                print(msg)
                raise RuntimeError(msg) from os.error


            # ---  database -------------------------------

            database = self.__database
            print("Using database %s" % database)

            # --- user  -------------------------------

            user = self.__user
            userCmd = '-u' + user

            # --- password  -------------------------------

            password = self.__password

            passwordCmd = ' '
            if (password != ''):
                passwordCmd = '-p' + password

            # --- sql command  -------------------------------

            #sqlCmd = '-e ' + 'show tables;'
            sqlCmd = '-e ' + '"show tables;"'

            # ---  command -------------------------------

            mySqlCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database + ' ' + sqlCmd
            print("mySqlCmd: " + mySqlCmd)

            # -------------------------------------------
            # do
            # -------------------------------------------

            proc = subprocess.Popen(mySqlCmd,stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True)
            output, errors = proc.communicate()

            # errors found ?
            if (len(errors) > 0):

                print()
                print('!!! Error on SQL command. Errors: "' + errors + '"')
                print()

            else:

                tableNames = output.splitlines()

                # remove first introduction line like 'Tables_in_joomla4x'
                headerLine = tableNames.pop (0)

                # valid result ?
                if (headerLine.lower().startswith('tables_')):

                    # for dbTableName in tableNames:
                    #     #first, middle, rest =
                    #     dbPrefix, searchStr, tableName = dbTableName.partition ('_')
                    #
                    #     if ('_rsg2_' in dbTableName):
                    #         self.__tableNames.append (tableName)
                    #         self.__tableNames_j4x.append (tableName)
                    #         print("    - j4: " + tableName)
                    #
                    #     if ('_rsgallery2_' in dbTableName):
                    #         self.__tableNames.append (tableName)
                    #         self.__tableNames_j3x.append (tableName)
                    #         print("    - j3: " + tableName)

                    for tableName in tableNames:

                        if ('_rsg2_' in tableName):
                            self.__tableNames.append (tableName)
                            self.__tableNames_j4x.append (tableName)
                            print("    - j4: " + tableName)

                        if ('_rsgallery2_' in tableName):
                            self.__tableNames.append (tableName)
                            self.__tableNames_j3x.append (tableName)
                            print("    - j3: " + tableName)

                    # one table exists: extract dp prefix

                    if (len(self.__tableNames)):
                        dbPrefix, searchStr, tableName = self.__tableNames[0].partition('_')
                        self.__dbPrefix = dbPrefix + searchStr

        except Exception as ex:
            print('!!! Exception: "' + str(ex) + '" !!!')
            print(traceback.format_exc())

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit readConfigFile')

        return


    # -------------------------------------------------------------------------------
    # ToDo: Return string instead of print
    def Text(self):
        # print ('    >>> Enter yyy: ')
        # print ('       XXX: "' + XXX + '"')

        ZZZ = ""

        try:
            print("All Tables: " + str(len(self.__tableNames)))
            for tableName in self.__tableNames:
                print('   - ' + tableName)

            print ()

            print("Tables j4x: " + str(len(self.__tableNames_j4x)))
            for tableName in self.__tableNames_j4x:
                print('   - ' + tableName)

            print ()

            print("Tables j3x: " + str(len(self.__tableNames_j3x)))
            for tableName in self.__tableNames_j3x:
                print('   - ' + tableName)

            print()

        except Exception as ex:
            print('!!! Exception: "' + str(ex) + '" !!!')
            print(traceback.format_exc())

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

    optlist, args = getopt.getopt(sys.argv[1:], 'd:p:u:m:12345h')

    database = 'joomla4x'
    user = 'root'
    password = ''

    mySqlPath = 'd:\\xampp\\mysql\\bin\\'

    for i, j in optlist:
        if i == "-d":
            database = j
        if i == "-p":
            password = j
        if i == "-u":
            user = j
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

    jRsg2Tables = jRsg2Tables(database, user, password, mySqlPath)
    jRsg2Tables.Text()

    print_end(start)

