#!/usr/bin/python

import os
import getopt
import sys
import subprocess
import traceback

from datetime import datetime

HELP_MSG = """
Reads config from external file LangManager.ini
The segment selection tells which segment(s) to use for configuration

usage: jDbPreFix.py -? nnn -? xxxx -? yyyy  [-h]
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
# jDbPreFix
# ================================================================================

# ================================================================================
# config
# ================================================================================

class jDbPreFix:
    """ Read tablenames from DB and extract the joomla prefix"""

    def __init__(self,
                 database='joomla4x',
                 user='root',
                 password='',
                 mySqlPath='d:\\xampp\\mysql\\bin\\'):

        print("Init jDbPreFix: ")

        self.__database = database
        self.__password = password
        self.__user = user
        self.__mySqlPath = mySqlPath

        # --- init  -------------------------------

        self.__dbPrefix = 'unknown_'

        # ---------------------------------------------
        # 
        # ---------------------------------------------

        self.readJoomlaDbPrefix ()

    # --- dbPrefix ---

    @property
    def dbPrefix(self):
        return self.__configurations['dbprefix']

    # @dbPrefix.setter
    # def database(self, dbPrefix):
    #     self.__dbPrefix = dbPrefix

    # --------------------------------------------------------------------
    # readJoomlaDbPrefix
    # --------------------------------------------------------------------

    def readJoomlaDbPrefix (self,
                        database='joomla4x',
                        user='root',
                        password='NOT_USED_AT_ALL'):

        try:
            print('*********************************************************')
            print('readJoomlaDbPrefix')
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

            #--- init prefix -------------------------------

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

                    # one table exists: extract dp prefix
                    if (len(tableNames)):
                        dbPrefix, searchStr, tableName = tableNames[0].partition('_')
                        self.__dbPrefix = dbPrefix + searchStr

        except Exception as ex:
            print('!!! Exception: "' + str(ex) + '" !!!')
            print(traceback.format_exc())

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit readConfigFile')

        return self.__dbPrefix

    # -------------------------------------------------------------------------------
    # ToDo: Return string instead of print
    def Text(self):
        # print ('    >>> Enter yyy: ')
        # print ('       XXX: "' + XXX + '"')

        ZZZ = ""

        try:
            print("dbPrefix: " + self.__dbPrefix)

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

    jDbPreFix = jDbPreFix(database, user, password, mySqlPath)
    jDbPreFix.Text()

    print_end(start)

