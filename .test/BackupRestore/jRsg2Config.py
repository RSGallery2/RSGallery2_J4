#!/usr/bin/python

import os
import getopt
import sys
import json
import subprocess
import traceback

from datetime import datetime
from jRsg2Tables import jRsg2Tables

#from .jRsg2Tables import jRsg2Tables


HELP_MSG = """
Reads RSGallery2 config with SQL commands
Values may be set manually for cases where db can't be read

!!! Actually it does just simulate it !!!


? old data from table

? write back old data
? write back new data


usage: jRsg2Config.py -? nnn -? xxxx -? yyyy  [-h]
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
  * (J4x) Write into Params -> read, insert and write back  
  * (J4x) Write into configuration table _rsgallery2_config
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
# jRsg2Config
# ================================================================================

# ================================================================================
# config
# ================================================================================

class jRsg2Config:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self,
                 database='joomla4x',
                 user='root',
                 password='',
                 mySqlPath='d:\\xampp\\mysql\\bin\\'):

        print("Init jRsg2Config: ")

        self.__database = database
        self.__password = password
        self.__user = user
        self.__mySqlPath = mySqlPath

        # --- RSG2 table names (existing) ----------------------------

        # Read from database
        self.__jRsg2TableNames = jRsg2Tables(database, user, password, mySqlPath)

        # --- init  -------------------------------

        self.__configurations = {}  # dictionary of name / value pairs
        self.__manifestParams = {}  # dictionary of name / value pairs
        self.__configurations_j3x = {}  # dictionary of name / value pairs

        #self.__dbPrefix = 'unknown_'
        self.__dbPrefix = self.__jRsg2TableNames.dbPrefix

        # # Simulate values
        # self.__configurations ['image_width'] = '800,600,400'
        # self.__configurations ['imgPath_root'] = '/images/rsgallery2'
        # self.__configurations ['imgPath_original'] = '/images/rsgallery/original'
        # self.__configurations ['imgPath_display'] = '/images/rsgallery/display'
        # self.__configurations ['imgPath_thumb'] = '/images/rsgallery/thumb'
        # self.__configurations ['imgPath_watermarked'] = '/images/rsgallery/watermarked'

        # self.__configurations [''] = ''
        # self.__configurations [''] = ''


        # ---------------------------------------------
        # assign variables from config file
        # ---------------------------------------------

        self.readRsg2Config ()

    # --- configurations ---

    @property
    def configurations(self):
        return self.__configurations

    @configurations.setter
    def configurations(self, configurations):
        self.__configurations = configurations

    # --- image_width ---

    @property
    def image_width(self):
        image_width = '-9999999.9999'
        if 'image_width' in self.__configurations:
            image_width =  self.__configurations ['image_width']
        else:
            if 'image_width' in self.__configurations_j3x:
                image_width = self.__configurations_j3x['image_width']

        return image_width

    # --- imgPath_root ---

    @property
    def imgPath_root(self):
        path = '-9999999.9999'
        if 'imgPath_root' in self.__configurations:
            path = self.__configurations['imgPath_root']

            # path may not start with root of disk
            if (path[0] == '/' or path[0] == '\\'):
                path = path [1:]

        return path

    # --- imgPath_original ---

    @property
    def j3x_imgPath_original(self):
        path = '-9999999.9999'
        if 'imgPath_original' in self.__configurations:
            path = self.__configurations['imgPath_original']

            # path may not start with root of disk
            if (path[0] == '/' or path[0] == '\\'):
                path = path [1:]
        else:
            if 'imgPath_original' in self.__configurations_j3x:
                path = self.__configurations_j3x['imgPath_original']

                # path may not start with root of disk
                if (path[0] == '/' or path[0] == '\\'):
                    path = path [1:]

        return path

    # --- imgPath_display ---

    @property
    def j3x_imgPath_display(self):
        path = '-9999999.9999'
        if 'imgPath_display' in self.__configurations:
            path = self.__configurations['imgPath_display']

            # path may not start with root of disk
            if (path[0] == '/' or path[0] == '\\'):
                path = path [1:]
        else:
            if 'imgPath_display' in self.__configurations_j3x:
                path = self.__configurations_j3x['imgPath_display']

                # path may not start with root of disk
                if (path[0] == '/' or path[0] == '\\'):
                    path = path[1:]

        return path

    # --- imgPath_thumb ---

    @property
    def j3x_imgPath_thumb(self):
        path = '-9999999.9999'
        if 'imgPath_thumb' in self.__configurations:
            path = self.__configurations['imgPath_thumb']

            # path may not start with root of disk
            if (path[0] == '/' or path[0] == '\\'):
                path = path [1:]
        else:
            if 'imgPath_thumb' in self.__configurations_j3x:
                path = self.__configurations_j3x['imgPath_thumb']

                # path may not start with root of disk
                if (path[0] == '/' or path[0] == '\\'):
                    path = path[1:]

        return path

    # --- imgPath_thumb ---

    @property
    def j3x_imgPath_watermarked(self):
        path = '-9999999.9999'
        if 'imgPath_watermarked' in self.__configurations:
            path = self.__configurations['imgPath_watermarked']

            # path may not start with root of disk
            if (path[0] == '/' or path[0] == '\\'):
                path = path [1:]
        else:
            path = '-9999999.9999'
            if 'imgPath_watermarked' in self.__configurations_j3x:
                path = self.__configurations_j3x['imgPath_watermarked']

                # path may not start with root of disk
                if (path[0] == '/' or path[0] == '\\'):
                    path = path[1:]

        return path

    # # --- propName ---
    #
    # @property
    # def propName(self):
    #     return self.__propName
    #
    # # --- propName ---
    #
    # @property
    # def propName(self):
    #     return self.__propName
    #

    # --------------------------------------------------------------------
    # readRsg2Config
    # --------------------------------------------------------------------

    def readRsg2Config (self,
                        database='',
                        user='',
                        password='NOT_USED_AT_ALL'):

        try:
            print('*********************************************************')
            print('readRsg2Config')
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

            self.__configurations = {}  # dictionary of name / value pairs
            self.__manifestParams = {}  # dictionary of name / value pairs
            self.__configurations_j3x = {}  # dictionary of name / value pairs

            #------------------------------------------------------------------
            #
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

            # -------------------------------------------
            # do extract com_rsgallery2 configuration parameters
            # -------------------------------------------

            # --- extensions table name  -------------------------------

            tableName = self.__dbPrefix + 'extensions'

            # --- sql command  -------------------------------

            sqlCmd = "SELECT params FROM " + tableName + " WHERE name='COM_RSGALLERY2';"
            #sqlCmd = "SELECT params FROM " + tableName + " WHERE element='com_rsgallery2';"

            # ---  command -------------------------------

            mySqlCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database + ' -e "' + sqlCmd + '"'
            print("mySqlCmd: " + mySqlCmd)

            proc = subprocess.Popen(mySqlCmd,stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True)

            output, errors = proc.communicate()

            # errors found ?
            if (len(errors) > 0):

                print()
                print('!!! Error on SQL command. Errors: "' + errors + '"')
                print()

            else:
                # tables exist ?
                if (len(output) > 0):

                    test1 = "path\\sub".replace ("\\", "/")
                    test2 = "path\\/sub".replace ("\\/", "/")

                    # remove any backslash to forward slash for folder
                    # "\\\\/" -> '/'
                    output = output.replace("\\\\/", "/")

                    params = output.splitlines()

                    # remove first introduction line like 'Tables_in_joomla4x'
                    headerLine = params.pop (0)

                    # valid result ?
                    if (headerLine.lower().startswith('params')):

                        # configurations exist
                        if (len(params) > 0):

                            # json params string from array
                            strParams1 = params[0]
                            strParams2 = strParams1.replace("\\/", "/")
                            strParams = strParams2

                            # to dictionary
                            self.__configurations = json.loads(strParams)

                    # -------------------------------------------
                    # do extract com_rsgallery2 manifest as dictionary
                    # -------------------------------------------

                    # Table __extensions:
                    # {"name":"com_rsgallery2","type":"component","creationDate":"18. Dec. 2018","author":"RSGallery2 Team","copyright":"(c) 2005-2018 RSGallery2 Team","authorEmail":"team@rsgallery2.org","authorUrl":"http:\/\/www.rsgallery2.org","version":"4.4.100","description":"COM_RSGALLERY2_XML_DESCRIPTION","group":"","filename":"rsgallery2"}
                    # {"name":"COM_RSGALLERY2","type":"component","creationDate":"17. Apr. 2020","author":"RSGallery2 Team","copyright":"(c) 2003-2020 RSGallery2 Team","authorEmail":"team2@rsgallery2.org","authorUrl":"https:\/\/www.rsgallery2.org","version":"5.0.0.4","description":"COM_RSGALLERY2_XML_DESCRIPTION","group":"","filename":"rsgallery2"}

                    # --- extensions table name  -------------------------------

                    tableName = self.__dbPrefix + 'extensions'

                    # --- sql command  -------------------------------

                    sqlCmd = "SELECT * FROM " + tableName + " WHERE name='COM_RSGALLERY2';"
                    #sqlCmd = "SELECT * FROM " + tableName + " WHERE element='com_rsgallery2';"

                    # ---  command -------------------------------

                    mySqlCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database + ' -e "' + sqlCmd + '"'
                    print("mySqlCmd: " + mySqlCmd)

                    proc = subprocess.Popen(mySqlCmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True)
                    output, errors = proc.communicate()


                    # errors found ?
                    if (len(errors) > 0):

                        print()
                        print('!!! Error on SQL command. Errors: "' + errors + '"')
                        print()

                    else:

                        dbRows = output.splitlines()

                        # remove first introduction line like 'Tables_in_joomla4x'
                        headerLine = dbRows [0].split('\t')[0]

                        # valid result ?
                        if (headerLine.lower().startswith('extension_id')):

                            # configurations exist
                            if (len(dbRows) > 1):

                                colNames = dbRows [0].split('\t')
                                colValues = dbRows [1].split('\t')

                                for idx, name in enumerate(colNames): #, start=1):
                                    value = colValues [idx]

                                    self.__manifestParams[name] = value
                else: # tables exist ?  if (len(output) > 0):

                    print()
                    print('!!! Error: No params for RSGallery2  (extension table, parallel to manifest ...')
                    print()

            # -------------------------------------------
            # do extract j3x version from table rsgallery2_config
            # -------------------------------------------

            if (self.__jRsg2TableNames.hasJ3xTables):

                # --- j3x config table name  -------------------------------

                tableName = self.__dbPrefix + 'rsgallery2_config'

                # --- sql command  -------------------------------

                sqlCmd = "SELECT name, value FROM " + tableName + " ORDER BY name ASC;"

                # ---  command -------------------------------

                mySqlCmd = mySqlExe + ' ' + userCmd + ' ' + passwordCmd + ' ' + database + ' -e "' + sqlCmd + '"'
                print("mySqlCmd: " + mySqlCmd)

                proc = subprocess.Popen(mySqlCmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True)
                output, errors = proc.communicate()


                # errors found ?
                if (len(errors) > 0):

                    print()
                    print('!!! Error on SQL command. Errors: "' + errors + '"')
                    print()

                else:

                    params = output.splitlines()

                    # remove first introduction line like 'Tables_in_joomla4x'
                    headerLine = params.pop (0)

                    # valid result ?
                    if (headerLine.lower().startswith('name')):

                        for paramLine in params:

                            name, value = paramLine.split('\t')
                            self.__configurations_j3x [name] = value


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
    def toFile(self, basePathFileName=''):
        # print ('    >>> Enter yyy: ')
        # print ('       XXX: "' + XXX + '"')

        ZZZ = ""

        try:

            #--- configuration from extension table ---------------------------------------------

            if (len(self.__configurations) > 0):
                filePathName = basePathFileName + '.config.ini'

                with open(filePathName, 'w') as configFile:

                    configFile.write("Configurations (table _extension): " + str(len(self.__configurations)) + "\n")

                    for key, value in self.__configurations.items():
                        configFile.write(key + " = '" + value + "'\n")

            #--- RSG2 manifest items from extension table ---------------------------------------------

            if (len(self.__manifestParams) > 0):
                filePathName = basePathFileName + '.manifest.ini'

                with open(filePathName, 'w') as manifestFile:

                    manifestFile.write("RSG2 manifest (table _extension): " + str(len(self.__manifestParams)) + "\n")

                    for key, value in self.__manifestParams.items():
                        manifestFile.write(key + " = '" + value + "'\n")

            #--- configuration from  table rsgallery2_config ---------------------------------------------

            if (len(self.__configurations_j3x) > 0):
                filePathName = basePathFileName + '.config_j3x.ini'

                with open(filePathName, 'w') as configFile:

                    configFile.write("Configurations J3x (table _rsgallery2_config): " + str(len(self.__configurations_j3x)) + "\n")

                    for key, value in self.__configurations_j3x.items():
                        configFile.write(key + " = '" + value + "'\n")

        except Exception as ex:
            print('!!! Exception: "' + str(ex) + '" !!!')
            print(traceback.format_exc())

    # -------------------------------------------------------------------------------
    # ToDo: Return string instead of print
    def Text(self):
        # print ('    >>> Enter yyy: ')
        # print ('       XXX: "' + XXX + '"')

        ZZZ = ""

        try:
            print("Configurations (extension): " + str(len(self.__configurations)))
            for key, value in self.__configurations.items():
                print("   " + key + " = '" + value + "'")

            print("manifest: " + str(len(self.__manifestParams)))
            for key, value in self.__manifestParams.items():
                print("   " + key + " = '" + value + "'")

            print("Configurations J3x: " + str(len(self.__configurations_j3x)))
            for key, value in self.__configurations_j3x.items():
                print("   " + key + " = '" + value + "'")

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

    #database = 'joomla4x'
    database = 'joomla3x'
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

    jRsg2Config = jRsg2Config(database, user, password, mySqlPath)
    jRsg2Config.Text()

    print_end(start)

