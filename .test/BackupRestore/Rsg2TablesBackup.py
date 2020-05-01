#!/usr/bin/python

import os
import getopt
import sys
import subprocess
import traceback

from datetime import datetime

from jRsg2Tables import jRsg2Tables
from jConfigFile import jConfigFile

#from .jRsg2Tables import jRsg2Tables



HELP_MSG = """
Exports dump of RSG2 tables from given database 
Seperate file for J3x and j4x tables
Configuration and pathes will be taken from configuration.php of referenced joomla installation

usage: Rsg2TablesBackup.py d database -u user -p password -f dumpPathFileName -m mySqlPath [-h]
	-p joomlaPath Path to joomla installation without last folder
	-n joomlaName folder and project name
    -f dumpPathFileName destination file where the dump will be written into

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
  *
  *  use joomla path for mysql ...   joomlaPath = 'd:/xampp/htdocs'
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
# Rsg2TablesBackup
# ================================================================================

class Rsg2TablesBackup:
    """ dumps RSG2 tables from given database """

    def __init__(self,
                 database='joomla4x',
                 user='root',
                 password='',
                 dumpPathFileName='Rsg2_TablesDump.sql',
                 mySqlPath=''):

        self.__database = database
        self.__password = password
        self.__user = user
        self.__dumpPathFileName = dumpPathFileName
        self.__mySqlPath = mySqlPath

        #--- RSG2 table names (existing) ----------------------------

        # Read from database
        self.__jRsg2TableNames = jRsg2Tables(database, user, password, mySqlPath)

    #--- database ---

    @property
    def database(self):
        return self.__database

    @database.setter
    def database(self, database):
        self.__database = database

    # #--- dbPrefix ---
    #
    # @property
    # def dbPrefix(self):
    #     return self.__dbPrefix
    #
    # @dbPrefix.setter
    # def database(self, dbPrefix):
    #     self.__dbPrefix = dbPrefix

    #--- user ---

    @property
    def user(self):
        return self.__user

    @user.setter
    def user(self, user):
        self.__user = user

    #--- password ---

    @property
    def password(self):
        return self.__password

    @password.setter
    def password(self, password):
        self.__password = password

    #--- dumpPathFileName ---

    @property
    def dumpPathFileName(self):
        return self.__dumpPathFileName

    @dumpPathFileName.setter
    def dumpPathFileName(self, dumpPathFileName):
        self.__dumpPathFileName = dumpPathFileName

    #--- mySqlPath ---

    @property
    def mySqlPath(self):
        return self.__mySqlPath

    @mySqlPath.setter
    def mySqlPath(self, mySqlPath):
        self.__mySqlPath = mySqlPath


    #--------------------------------------------------------------------
    # do dump tables
    #--------------------------------------------------------------------

    def doBackupTables (self, dumpPathFileName='', dumpDatabase=''):
        try:
            print('*********************************************************')
            print('doBackupTables')
            print('dumpPathFileName (in): ' + dumpPathFileName)
            print('---------------------------------------------------------')

            #--- save dump file name if given  -------------------------------
            
            if (dumpPathFileName != ''):
                self.__dumpPathFileName = dumpPathFileName

            print('dumpPathFileName (used): ' + self.__dumpPathFileName)

            # --- save dump dumpDatabase name if given  -------------------------------

            if (dumpDatabase != ''):
                self.__database = dumpDatabase

            print('dumpDatabase (used): ' + self.__database)

            #--- check for mysqlDump exe ------------------------------

            mySqlDumpExe = os.path.join (self.__mySqlPath, "mysqldump.exe")
            print("mySqlDump: " + mySqlDumpExe)

            if (not os.path.isfile(mySqlDumpExe)):
                msg = 'mysqldump.exe file did not exist: "' + mySqlDumpExe
                print(msg)
                raise RuntimeError(msg) from os.error

            #---  database -------------------------------

            database = self.__database
            print("Dumping of db %s" % database)

            #--- user  -------------------------------

            user = self.__user
            userCmd = '-u' + user            
            
            #--- password  -------------------------------

            password = self.__password

            passwordCmd = ' '
            if (password != ''):
                passwordCmd = '-p' + password

            #--- filenames for J4x and j3x tables
            parts = self.__dumpPathFileName.rsplit('.', 1)

            dumpPathFileName_j4x = parts[0] + '.' + 'j4x' + '.' + parts[1]
            dumpPathFileName_j3x = parts[0] + '.' + 'j3x' + '.' + parts[1]

            #-------------------------------------------
            # do dump j4x
            #-------------------------------------------

            try:
                # tables may not exist
                if (not self.__jRsg2TableNames.hasJ4xTables):
                    print('   > NO j4x tables found')

                # j4x tables exist
                if (self.__jRsg2TableNames.hasJ4xTables):

                    print('do dump j4x tables')

                    #--- tables command j4x -------------------------------

                    tablesCmd = ' '.join (self.__jRsg2TableNames.tables_j4x)

                    #---  dump command -------------------------------

                    dumpCmd = mySqlDumpExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database  + ' ' + tablesCmd
                    print("cmd: " + dumpCmd)

                    #--- do dump --------------------------------------

                    with open(dumpPathFileName_j4x, 'w') as dumpFile:

                        proc = subprocess.Popen(dumpCmd,
                                                stdout=dumpFile)
                        proc.communicate()
            finally:
                pass
            
            #-------------------------------------------
            # do dump j3x
            #-------------------------------------------

            try:
                if (not self.__jRsg2TableNames.hasJ3xTables):
                    print('   > NO j3x tables found')

                # j3x tables exist
                if (self.__jRsg2TableNames.hasJ3xTables):
                    print('do dump j3x tables')

                    #--- tables command j3x -------------------------------

                    tablesCmd = ' '.join (self.__jRsg2TableNames.tables_j3x)

                    #---  dump command -------------------------------

                    dumpCmd = mySqlDumpExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database  + ' ' + tablesCmd
                    print("cmd: " + dumpCmd)

                    #--- do dump --------------------------------------

                    with open(dumpPathFileName_j3x, 'w') as dumpFile:

                        proc = subprocess.Popen(dumpCmd,
                                                stdout=dumpFile)
                        proc.communicate()
            finally:
                pass

        except Exception as ex:
            print('!!! Exception: "' + str(ex) + '" !!!')
            print(traceback.format_exc())

        #--------------------------------------------------------------------
        #
        #--------------------------------------------------------------------

        finally:
            print('exit doBackupTables')

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

    # optlist, args = getopt.getopt(sys.argv[1:], 'd:p:u:f:m:j:12345h')
    #
    # database = 'joomla4x'
    # user = 'root'
    # password = ''
    # backupBasePath = '../../../RSG2_Backup'
    # dumpPathFileName = 'Rsg2_TablesDump.sql'
    # mySqlPath = 'd:\\xampp\\mysql\\bin\\'


    optlist, args = getopt.getopt(sys.argv[1:], 'p:n:f:m:12345h')

    joomlaPath = 'd:/xampp/htdocs'
    joomlaName = 'joomla3x'
    #backupBasePath = '../../../RSG2_Backup'

    #dumpFileName = 'Rsg2_TablesDump.20200414_215456.sql' # 'Rsg2_TablesDump'
    #dumpFileName = os.path.join(backupBasePath, 'testRestore\Rsg2_TablesDump.sql') # 'Rsg2_TablesDump'
    dumpPathFileName = "..\..\..\RSG2_Backup\\joomla3x.20200430_171320\Rsg2_TablesDump.j3x.ttt.sql"


    for i, j in optlist:
        if i == "-p":
            joomlaPath = j
        if i == "-n":
            joomlaName = j
        if i == "-f":
            dumpPathFileName = j
            
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

#    mySqlPath = os.path.join(os.path.dirname(joomlaPath), 'mysql', 'bin')


    # --- Joomla configuration parameter ----------------------------

    jConfigPathFileName = os.path.join(joomlaPath, joomlaName, 'configuration.php')
    joomlaCfg = jConfigFile(jConfigPathFileName)

    mySqlPath = os.path.join(os.path.dirname(joomlaPath), 'mysql', 'bin')

    # --- do backup ----------------------------

    dumpPathFileName = AddDateToFileName (dumpPathFileName)
    rsg2TablesBackup = Rsg2TablesBackup (joomlaCfg.database, joomlaCfg.user, joomlaCfg.password,
                                         dumpPathFileName, mySqlPath)

    rsg2TablesBackup.doBackupTables()

    print_end(start)

