#!/usr/bin/python

import os
import getopt
import sys
import subprocess
import traceback

from datetime import datetime
from jConfigFile import jConfigFile

HELP_MSG = """
Restores dump of RSG2 tables from given database 

usage: Rsg2TablesRestore.py -d database -u user -p password -f dumpFileName -m mySqlPath -j isUseJ3xTables  [-h]
    -d database Name of database for restore
    -u user User name of database for restore
    -p password Password of database for restore
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
  * doRestoreDumpTables -> add database parameter
  * create ..\..\data for exchange of sql ...
  * 
  * Destination prefix could be read automaticakly
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
# Rsg2TablesRestore
# ================================================================================

class Rsg2TablesRestore:
    """ dumps RSG2 tables from given database """

    def __init__(self,
                 database='test',  # 'joomla4x',
                 dbPrefix='rest_',  # restore, 
                 user='root',
                 password='',
                 dumpFileName='Rsg2_TablesDump.20200414_215456.sql',  # 'Rsg2_TablesDump',
                 mySqlPath='d:\\xampp\\mysql\\bin\\'):

        print("Init Rsg2TablesRestore: ")

        self.__database = database
        self.__dbPrefix = dbPrefix
        self.__password = password
        self.__user = user
        self.__dumpFileName = dumpFileName

        mySqlPath = os.path.join(os.path.dirname(joomlaPath), 'mysql', 'bin')

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

    def doRestoreDumpTables(self, dumpFileName='', dumpDatabase='', dbPrefix=''):
        # ToDo: Check if name exists otherwise standard
        # ToDo: try catch ...
        try:
            print('*********************************************************')
            print('doRestoreDumpTables')
            print('dumpFileName (in): ' + dumpFileName)
            print('---------------------------------------------------------')

            # --- save dump file name if given  -------------------------------

            if (dumpFileName != ''):
                self.__dumpFileName = dumpFileName

            print('dumpFileName (used): ' + self.__dumpFileName)

            # --- save dump database name if given  -------------------------------

            if (dumpDatabase == ''):
                dumpDatabase = self.__database

            self.__database = dumpDatabase

            print('dumpDatabase (used): ' + self.__database)

            # --- save dump dbPrefix name if given  -------------------------------

            if (dbPrefix == ''):
                dbPrefix = self.__dbPrefix

            self.__dbPrefix = dbPrefix

            print('dbPrefix (used): ' + self.__dbPrefix)

            #------------------------------------------------------------------
            # read and patch sql file data
            #------------------------------------------------------------------

            # --- read dump file sql data -------------------------------

            print("Read %s" % self.__dumpFileName)

            #--- Detect dumped joomla dbPrefix ------------------------------------

            # fall back
            oldPrefix = dbPrefix

            # first lines until old prefix can be determined
            with open(self.__dumpFileName, encoding="utf-8") as sqlFile:

                # -- Table structure for table `j4_rsg2_galleries`
                toCheck = 'Table structure for table `'
                separator = '_'

                for line in sqlFile:
                    # line contains check part
                    idxCheck = line.find(toCheck)

                    # found: extract
                    if (idxCheck > -1):
                        # remove check string
                        partLine = line[idxCheck + len(toCheck):]

                        # seek end of preIndex
                        sepIdx = partLine.index(separator)

                        # extract old db
                        oldPrefix = partLine [:sepIdx + 1]
                        break

            #--- read sql file, exchange prefix ---------------------------------

            with open(self.__dumpFileName, encoding="utf-8") as sqlFile:
                sqlLines = sqlFile.read()

                # exchange joomla db prefix
                sqlLines = sqlLines.replace(oldPrefix, dbPrefix)

                # display first lines (ToDo: may be commented later )

                lines = sqlLines.split('\n', 30)
                # for line in lines:
                for index, line in enumerate(lines):
                    print(index + 1, line)
                    if index > 25:
                        break

                lines = ""

            #--- file lines as bytes needed ---------------------------------

            # did work but not needed
            # # read sql content as byte
            # with open(self.__dumpFileName, 'rb') as sqlFile:
            #    sqlBytes = sqlFile.read()

            sqlBytes = sqlLines.encode()

            #------------------------------------------------------------------
            # prepare sql command
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


            # --- import dump command -------------------------------

            insertCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database
            print("cmd: " + insertCmd)

            # -------------------------------------------
            # do import dump
            # -------------------------------------------

            proc = subprocess.Popen(insertCmd, shell=True, stdin=subprocess.PIPE,
                                    stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            proc.stdin.write(sqlBytes)
            proc.communicate()[0]
            proc.stdin.close()

        except Exception as ex:
            print('x Exception:' + ex)
            print(traceback.format_exc())

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit doRestoreDumpTables')

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

    optlist, args = getopt.getopt(sys.argv[1:], 'p:n:f:m:12345h')

    joomlaPath = 'd:/xampp/htdocs'

    joomlaName = 'joomla3x'

    backupBasePath = '../../../RSG2_Backup'

    dumpFileName = 'Rsg2_TablesDump.20200414_215456.sql' # 'Rsg2_TablesDump'
    dumpFileName = os.path.join(backupBasePath, 'testRestore\Rsg2_TablesDump.sql') # 'Rsg2_TablesDump'
    #dumpFileName = "..\..\..\RSG2_Backup\\joomla3x.20200430_171320\Rsg2_TablesDump.j3x.sql"

    for i, j in optlist:
        if i == "-p":
            joomlaPath = j
        if i == "-n":
            joomlaName = j
        if i == "-f":
            dumpFileName = j

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

    # --- Joomla configuration parameter ----------------------------

    jConfigPathFileName = os.path.join(joomlaPath, joomlaName, 'configuration.php')
    joomlaCfg = jConfigFile(jConfigPathFileName)

    mySqlPath = os.path.join(os.path.dirname(joomlaPath), 'mysql', 'bin')

    # --- do restore ----------------------------

    rsg2TablesRestore = Rsg2TablesRestore(joomlaCfg.database, joomlaCfg.dbPrefix, joomlaCfg.user, joomlaCfg.password, dumpFileName, mySqlPath)

    rsg2TablesRestore.doRestoreDumpTables()

    print_end(start)

