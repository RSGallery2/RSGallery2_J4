#!/usr/bin/python

import os
import getopt
import sys

from datetime import datetime

HELP_MSG = """
Reads config from external file LangManager.ini
The segment selection tells which segment(s) to use for configuration

usage: RestoreRsg2.py -? nnn -? xxxx -? yyyy  [-h]
	-? nnn
	-? 

	
	-h shows this message
	
	-1 RestoreRsg2.py
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
# RestoreRsg2
# ================================================================================

class RestoreRsg2:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self, configPathFileName=''):

        print("Init RestoreRsg2: ")
        print("configPathFileName: " + configPathFileName)

        self.__configPathFileName  = './LangManager.ini'
        if (len(configPathFileName) > 0):
            self.__configPathFileName = configPathFileName

        self.__isWriteEmptyTranslations = False
        self.__isOverwriteSrcFiles = False
        self.__isDoBackup = False

        self.__baseSrcPath = ""
        self.__baseTrgPath = ""

        self.__comparePaths = {} # compare multiple paths

        # ---------------------------------------------
        # assign variables from config file
        # ---------------------------------------------

        self.readConfigFile (self.__configPathFileName)

    # --- isWriteEmptyTranslations ---

    @property
    def isWriteEmptyTranslations(self):
        return self.__isWriteEmptyTranslations

    @isWriteEmptyTranslations.setter
    def isWriteEmptyTranslations(self, isWriteEmptyTranslations):
        self.__isWriteEmptyTranslations = isWriteEmptyTranslations

    # --- isOverwriteSrcFiles ---

    @property
    def isOverwriteSrcFiles(self):
        return self.__isOverwriteSrcFiles

    @isOverwriteSrcFiles.setter
    def isOverwriteSrcFiles(self, isOverwriteSrcFiles):
        self.__isOverwriteSrcFiles = isOverwriteSrcFiles

    # --- isDoBackup ---

    @property
    def isDoBackup(self):
        return self.__isDoBackup

    @isDoBackup.setter
    def isDoBackup(self, isDoBackup):
        self.__isDoBackup = isDoBackup

    # --- baseSrcPath ---

    @property
    def baseSrcPath(self):
        return self.__baseSrcPath

    @baseSrcPath.setter
    def baseSrcPath(self, baseSrcPath):
        self.__baseSrcPath = baseSrcPath

    # --- baseTrgPath ---

    @property
    def baseTrgPath(self):
        return self.__baseTrgPath

    @baseTrgPath.setter
    def baseTrgPath(self, baseTrgPath):
        self.__baseTrgPath = baseTrgPath

    # --- comparePaths ---

    @property
    def comparePaths(self):
        return self.__comparePaths

    @comparePaths.setter
    def comparePaths(self, comparePaths):
        self.__comparePaths = comparePaths

    # --------------------------------------------------------------------
    #
    # --------------------------------------------------------------------
    # https://wiki.python.org/moin/ConfigParserExamples

    def readConfigFile (self, iniFileName):
        # ToDo: Check if name exists otherwise standard
        # ToDo: try catch ...
        try:
            print('*********************************************************')
            print('readConfigFile')
            print('iniFileName: ' + iniFileName)
            print('---------------------------------------------------------')

            #--- define used segments -------------------------------

            configFile = configparser.ConfigParser()
            configFile.read(iniFileName)

            sourcePath = configFile['selection']['sourcePath']
            task = configFile['selection']['task']

            #--- in selected segments ----------------------------------------------

            self.__isWriteEmptyTranslations = configFile.getboolean(task, 'isWriteEmptyTranslations', fallback=False)
            print('__isWriteEmptyTranslations: ', str(self.__isWriteEmptyTranslations))

            self.__isOverwriteSrcFiles = configFile.getboolean(sourcePath, 'isOverwriteSrcFiles', fallback=False)
            print('__isOverwriteSrcFiles: ', str(self.__isOverwriteSrcFiles))

            self.__isDoBackup = configFile.getboolean(sourcePath, 'isDoBackup', fallback=False)
            print('__isDoBackup: ', str(self.__isDoBackup))


            self.__baseSrcPath = configFile.get (sourcePath, 'sourceFolder')
            print('__baseSrcPath: ', str(self.__baseSrcPath))

            self.__baseTrgPath = configFile.get (sourcePath, 'targetFolder')
            print('__baseTrgPath: ', str(self.__baseTrgPath))

            #--- folder list ---------------------------------

            self.__comparePaths = {}

            if (sourcePath == 'jgerman_wip_all'):

                options = configFile.options(sourcePath)
                for option in options:
                    try:
                        if ('sourcefolder' in option):
                            sourceFolder = configFile.get(sourcePath, option)
                        if ('targetfolder' in option):
                            targetFolder = configFile.get(sourcePath, option)
                            self.__comparePaths[sourceFolder] = targetFolder
                            print('All: sourcePath: ' + sourceFolder + ' targetPath: ' + targetFolder)

                    except:
                        print("exception on %s!" % option)

            else:
                # standard
                self.__comparePaths [self.__baseSrcPath] = self.__baseTrgPath
                print('All: sourcPath: ' + self.__baseSrcPath + ' targetPath: ' + self.__baseTrgPath)

        except Exception as ex:
            print(ex)

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit readConfigFile')

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

    LeftPath = ''
    RightPath = ''

    for i, j in optlist:
        if i == "-l":
            LeftPath = j
        if i == "-r":
            RightPath = j

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

    RestoreRsg2 = RestoreRsg2()

    print_end(start)

