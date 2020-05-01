#!/usr/bin/python

import os
import getopt
import sys
import traceback
import platform

from datetime import datetime

from jConfigFile import jConfigFile
from jRsg2Config import jRsg2Config
from Rsg2TablesBackup import Rsg2TablesBackup
from Rsg2ImagesBackup import Rsg2ImagesBackup
# from .jConfigFile import jConfigFile
# from .jRsg2Config import jRsg2Config
# from .Rsg2TablesBackup import Rsg2TablesBackup
# from .Rsg2ImagesBackup import Rsg2ImagesBackup


HELP_MSG = """
Backups RSG2 database tables and RSG2 files
the necessary data will be read from the configuration.php  
The backup file will be written into a folder in the backup directory
The destination folder name will be created using joomlaName and actual date/time  

usage: BackupRsg2.py -p joomlaPath -n joomlaName -b backupBasePath  [-h]
	-p joomlaPath Path to joomla installation without last folder
	-n joomlaName folder and project name 
	-b backupBasePath Where the resulting files will be stored
	
    joomlaPath = 'd:\\xampp\\htdocs'
    joomlaName = 'joomla4x'
    backupBasePath = '..\\..\\..\\RSG2_Backup'

	
	-h shows this message
	
	-1 
	-2 
	-3 
	-4 
	-5 
	
	
	example: BackupRsg2.py -p d:\\xampp\htdocs -n joomla4x -b ..\\..\\..\\RSG2_Backup 
	
	
------------------------------------
ToDo:
  * use as argument    mySqlPath = 'd:/xampp/mysql/bin/'
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
# BackupRsg2
# ================================================================================

class BackupRsg2:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self,
                 joomlaPath='d:/xampp/htdocs',
                 joomlaName='joomla4x',
                 backupBasePath='../../../RSG2_Backup'):

        print("Init BackupRsg2: ")
        print("joomlaPath: " + joomlaPath)
        print("joomlaName: " + joomlaName)
        print("backupBasePath: " + backupBasePath)

        self.__joomlaPath = joomlaPath
        self.__joomlaName = joomlaName
        self.__backupBasePath = backupBasePath

        mySqlPath = os.path.join(os.path.dirname(joomlaPath), 'mysql', 'bin')
        self.__mySqlPath = mySqlPath

        # ---------------------------------------------
        # Read variables from config files
        # ---------------------------------------------

        try:
            #--- Joomla configuration parameter ----------------------------

            jConfigPathFileName = os.path.join (self.__joomlaPath, self.__joomlaName, 'configuration.php')
            self.__joomlaCfg = jConfigFile(jConfigPathFileName)

            #--- RSG2 database configuration parameter ----------------------------

            self.__rsg2Cfg = jRsg2Config(
                self.__joomlaCfg.database,
                self.__joomlaCfg.user,
                self.__joomlaCfg.password,
                self.__mySqlPath
            )

        finally:
            pass

        return
    
    # # --- isWriteEmptyTranslations ---
    #
    # @property
    # def isWriteEmptyTranslations(self):
    #     return self.__isWriteEmptyTranslations
    #
    # @isWriteEmptyTranslations.setter
    # def isWriteEmptyTranslations(self, isWriteEmptyTranslations):
    #     self.__isWriteEmptyTranslations = isWriteEmptyTranslations

    # --------------------------------------------------------------------
    #
    # --------------------------------------------------------------------

    def doBackup (self, backupPath=''):

        try:
            print('*********************************************************')
            print('doBackup')
            print('backupPath='': ' + backupPath)
            print('---------------------------------------------------------')

            # --- create auto backup path ----------------------------------------

            if (backupPath == ''):
                backupPath =  os.path.join (self.__backupBasePath, AddDateToName (self.__joomlaName))

            # self.__backupPath  = backupPath

            print('backupPath (used): ' + backupPath)
            print('backupPath (abspath): ' + os.path.abspath(backupPath))

            

            #--- Create path if not already exists -------------------------------

            if (not os.path.isdir(backupPath)):
                os.makedirs(backupPath)


            #--- Create filename with source PC name -------------------------------

            hostName = str(platform.node())
            print("HostName (PC): " + hostName)

            hostPathFileName = os.path.join (backupPath, hostName + '.txt')
            with open(hostPathFileName, 'w') as manifestFile:

                #manifestFile.write("RSG2 backup on " + hostName + "\n")
                manifestFile.write("hostName " + hostName + "\n")
                manifestFile.write("date: " + '{0:%Y%m%d_%H%M%S}'.format(datetime.now()) + "\n")
                manifestFile.write("joomlaPath: " + self.__joomlaPath + "\n")
                manifestFile.write("joomlaName: " + self.__joomlaName + "\n")
                manifestFile.write("backupBasePath: " + os.path.abspath(backupBasePath) + "\n")

            # --------------------------------------------------------------------
            # Backup RSG2 configurations from database
            # --------------------------------------------------------------------

            cfgPathFileName = os.path.join (backupPath, 'Rsg2_config')
            self.__rsg2Cfg.toFile (cfgPathFileName)

            # --------------------------------------------------------------------
            # Dump database
            # --------------------------------------------------------------------

            database = self.__joomlaCfg.database
            dbPrefix = self.__joomlaCfg.dbPrefix
            user = self.__joomlaCfg.user
            password = self.__joomlaCfg.password

            dumpPathFileName = os.path.join (backupPath, 'Rsg2_TablesDump.sql')
            mySqlPath = self.__mySqlPath
            isUseJ3xTables = True

            rsg2TablesBackup = Rsg2TablesBackup(database, user, password, dumpPathFileName, mySqlPath)

            #--- Dump database --------------------------------------------------

            rsg2TablesBackup.doBackupTables()

            # --------------------------------------------------------------------
            # collect all images
            # --------------------------------------------------------------------

            #backupPath = '../../../RSG2_Backup'
            joomlaPath = os.path.join (self.__joomlaPath, self.__joomlaName)
            image_width = self.__rsg2Cfg.image_width # '800,600,400'
            imgPath_root = self.__rsg2Cfg.imgPath_root #'imgPath_root'
            imgPath_original = self.__rsg2Cfg.j3x_imgPath_original #'imgPath_original'
            imgPath_display = self.__rsg2Cfg.j3x_imgPath_display #'imgPath_display'
            imgPath_thumb = self.__rsg2Cfg.j3x_imgPath_thumb #'imgPath_thumb'
            imgPath_watermarked = self.__rsg2Cfg.j3x_imgPath_watermarked #'imgPath_watermarked'

            rsg2ImagesBackup = Rsg2ImagesBackup(
                backupPath,
                joomlaPath,
                image_width,
                imgPath_root,
                imgPath_original,
                imgPath_display,
                imgPath_thumb,
                imgPath_watermarked)

            rsg2ImagesBackup.doCopy()


        except Exception as ex:
            print('x Exception:' + ex)
            print(traceback.format_exc())

        # --------------------------------------------------------------------
        #
        # --------------------------------------------------------------------

        finally:
            print('exit doBackup')

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


def AddDateToName(Name):

    DateTime = '{0:%Y%m%d_%H%M%S}'.format(datetime.now())

    dateName = Name + '.' + DateTime

    return dateName

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

    optlist, args = getopt.getopt(sys.argv[1:], 'b:p:n:12345h')

    joomlaPath = 'd:/xampp/htdocs'
    #joomlaPath = 'e:/xampp/htdocs'
    #joomlaPath = 'f:/xampp/htdocs'
    #joomlaPath = 'e:/xampp_J2xJ3x/htdocs'
    #joomlaPath = 'f:/xampp_J2xJ3x/htdocs'


    joomlaName = 'joomla4x'
    #joomlaName = 'joomla3x'
    
    ##joomlaName = 'joomla3x'
    ##joomlaName = 'joomla3xMyGallery'
    #joomlaName = 'joomla3xNextRelease'
    #joomlaName = 'joomla3xRelease'
    #joomlaName = 'joomla4x'
    #joomlaName = 'joomla4xfrom3x'
    #joomlaName = 'joomla4xInstall'

    backupBasePath = '../../../RSG2_Backup'

    for i, j in optlist:
        if i == "-p":
            joomlaPath = j
        if i == "-n":
            joomlaName = j
        if i == "-b":
            backupBasePath = j

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

    BackupRsg2 = BackupRsg2(joomlaPath, joomlaName, backupBasePath)
    BackupRsg2.doBackup ()

    print_end(start)

