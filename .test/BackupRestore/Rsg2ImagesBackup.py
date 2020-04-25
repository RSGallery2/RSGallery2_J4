#!/usr/bin/python

import os
import getopt
import sys
import shutil

from datetime import datetime

HELP_MSG = """
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
  * rsg 2 config paths start mostly with '\' care for missing '\' or remove this on entering
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
# Rsg2ImagesBackup
# ================================================================================

class Rsg2ImagesBackup:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self,
                 backupPath          = '../../../RSG2_Backup',
                 joomlaPath          = 'd:/xampp/htdocs/joomla4x',
                 image_width         = '800,600,400',
                 imgPath_root        = 'imgPath_root',
                 imgPath_original    = 'imgPath_original',
                 imgPath_display     = 'imgPath_display',
                 imgPath_thumb       = 'imgPath_thumb',
                 imgPath_watermarked = 'imgPath_watermarked'):

        print("Init Rsg2ImagesBackup: ")
        print("backupPath: " + backupPath)
        print("joomlaPath: " + joomlaPath)
        print("image_width: " + image_width)
        print("imgPath_root: " + imgPath_root)
        print("imgPath_original: " + imgPath_original)
        print("imgPath_display: " + imgPath_display)
        print("imgPath_thumb: " + imgPath_thumb)
        print("imgPath_watermarked: " + imgPath_watermarked)

        self.__backupPath =          backupPath
        self.__joomlaPath =          joomlaPath
        self.__image_width =         image_width
        self.__imgPath_root =        imgPath_root
        self.__imgPath_original =    imgPath_original
        self.__imgPath_display =     imgPath_display
        self.__imgPath_thumb =       imgPath_thumb
        self.__imgPath_watermarked = imgPath_watermarked

        #--- source / destination paths ------------------------------------------

        self.__copyPaths = {}  # dictionary of source / destination paths pairs

        # ---------------------------------------------
        # prepare source / destination paths
        # ---------------------------------------------

        self.createCopyPaths ()


    # --- propName ---

    @property
    def propName(self):
        return self.__propName

    @propName.setter
    def propName(self, propName):
        self.__propName = propName

    # # --- propName ---
    #
    # @property
    # def propName(self):
    #     return self.__propName
    #
    # @propName.setter
    # def propName(self, propName):
    #     self.__propName = propName
    #
    # # --- propName ---
    #
    # @property
    # def propName(self):
    #     return self.__propName
    #
    # @propName.setter
    # def propName(self, propName):
    #     self.__propName = propName
    #

    # --------------------------------------------------------------------
    #
    # --------------------------------------------------------------------

    def createCopyPaths(self):
        print('    >>> Enter createCopyPaths: ')

        # self.__joomlaPath
        # self.__backupPath

        # self.__image_width =         image_width
        # self.__imgPath_root =        imgPath_root

        # self.__imgPath_original =    imgPath_original
        # self.__imgPath_display =     imgPath_display
        # self.__imgPath_thumb =       imgPath_thumb
        # self.__imgPath_watermarked = imgPath_watermarked

        '--- j4X ------------------------------------------------'

        src = os.path.join (self.__joomlaPath, self.__imgPath_root)
        dst = os.path.join  (self.__backupPath, self.__imgPath_root)
        self.__copyPaths [src] = dst

        '--- j3X ------------------------------------------------'

        src = os.path.join (self.__joomlaPath, self.__imgPath_original)
        dst = os.path.join  (self.__backupPath, self.__imgPath_original)
        self.__copyPaths [src] = dst

        src = os.path.join (self.__joomlaPath, self.__imgPath_display)
        dst = os.path.join  (self.__backupPath, self.__imgPath_display)
        self.__copyPaths [src] = dst

        src = os.path.join (self.__joomlaPath, self.__imgPath_thumb)
        dst = os.path.join  (self.__backupPath, self.__imgPath_thumb)
        self.__copyPaths [src] = dst

        if (len(self.__imgPath_watermarked)):
            src = os.path.join (self.__joomlaPath, self.__imgPath_watermarked)
            dst = os.path.join  (self.__backupPath, self.__imgPath_watermarked)
            self.__copyPaths [src] = dst

        # print ('       XXX: "' + XXX + '"')
        print('    >>> Exit createCopyPaths: ')

    # --------------------------------------------------------------------
    # doCopy
    # --------------------------------------------------------------------
    # https://wiki.python.org/moin/ConfigParserExamples

    def doCopy (self):

        try:
            print('*********************************************************')
            print('doCopy')
            print('---------------------------------------------------------')

            # all path pairs
            for src, dst in self.__copyPaths.items():

                # source path found ?
                if (os.path.isdir(src)):
                    print('   "' + src + '" ==> "' + dst + '"')

                    #--- copy -----------------------------------------------------------------

                    shutil.copytree (src, dst)


        except Exception as ex:
            print(ex)

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

    optlist, args = getopt.getopt(sys.argv[1:], 'b:j:s:r:o:d:t:w:12345h')

    backupPath = '../../../RSG2_Backup' # ? Joomla4 +  date ?
    joomlaPath = 'd:/xampp/htdocs/joomla4x'
    # joomlaPath = 'd:/xampp/htdocs/joomla3x'
    # joomlaPath = 'e:/xampp_J2xJ3x/htdocs/Joomla25'
    # joomlaPath = 'e:/xampp_J2xJ3x/htdocs/Joomla35'

    image_width = '' # sizes
    imgPath_root = '/images/rsgallery2'
    imgPath_original = '/images/rsgallery/original'
    imgPath_display = '/images/rsgallery/display'
    imgPath_thumb = '/images/rsgallery/thumb'
    imgPath_watermarked = '/images/rsgallery/watermarked'

    for i, j in optlist:
        if i == "-b":
            backupPath = j
        if i == "-j":
            joomlaPath = j
        if i == "-s":
            image_width = j
        if i == "-r":
            imgPath_root = j
        if i == "-o":
            imgPath_original = j
        if i == "-d":
            imgPath_display = j
        if i == "-t":
            imgPath_thumb = j
        if i == "-w":
            imgPath_watermarked = j

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

    rsg2ImagesBackup = Rsg2ImagesBackup(
                                         backupPath,
                                         joomlaPath,
                                         image_width,
                                         imgPath_root,
                                         imgPath_original,
                                         imgPath_display,
                                         imgPath_thumb,
                                         imgPath_watermarked)

    rsg2ImagesBackup.doCopy ()

    print_end(start)

