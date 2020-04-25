#!/usr/bin/python

import os
import getopt
import sys

from datetime import datetime

HELP_MSG = """
Reads items  from joomla! (R) configuration file to provide key value access 

------------------------------------
ToDo:
  * prepare access clases for number (int double), bool and ? text ?  
  * 
  * 
  * 
  * 
  * 

"""

# ================================================================================
# config
# ================================================================================

class jConfigFile:
    """ config read from file. First segment in file defines the used segment with configuration items """

    def __init__(self, configPathFileName=''):

        print("Init _emptyClass: ")
        print("configPathFileName: " + configPathFileName)

        self.__configPathFileName  = 'd:/xampp/htdocs/joomla4x/configuration.php'
        if (len(configPathFileName) > 0):
            self.__configPathFileName = configPathFileName

        self.__isWriteEmptyTranslations = False

        self.__configurations = {}  # dictionary of name / value pairs

        # ---------------------------------------------
        # assign variables from config file
        # ---------------------------------------------

        self.readConfigFile (self.__configPathFileName)

    # --- configurations ---

    @property
    def configurations(self):
        return self.__configurations

    @configurations.setter
    def configurations(self, configurations):
        self.__configurations = configurations

    # --- database ---

    @property
    def database(self):
        return self.__configurations ['db']

    #--- dbPrefix ---

    @property
    def dbPrefix(self):
        return self.__configurations ['dbprefix']
    
    # @dbPrefix.setter
    # def database(self, dbPrefix):
    #     self.__dbPrefix = dbPrefix

    # --- user ---

    @property
    def user(self):
        return self.__configurations ['user']

    # --- password ---

    @property
    def password(self):
        return self.__configurations ['password']

    # --------------------------------------------------------------------
    #
    # --------------------------------------------------------------------

    def readConfigFile (self, configFileName):
        # ToDo: Check if name exists otherwise standard
        # ToDo: try catch ...
        try:
            print('*********************************************************')
            print('readConfigFile')
            print('configFileName: ' + configFileName)
            print('---------------------------------------------------------')

            self.__configurations = {}

            # prepare constants
            startText = 'public $'
            startLength = len(startText)

            # ---------------------------------------------
            # Read file
            # ---------------------------------------------

            # --- save config file name if given  -------------------------------

            if (configFileName != ''):
                self.__configFileName = configFileName

            print('configFileName (used): ' + self.__configFileName)

            if (not os.path.isfile(configFileName)):
                print('!!! warning configFileName does not exist')

            if (os.path.isfile(configFileName)):
                print('Found fileName: ' + configFileName)
                # print ('fileName: ' + fileName)

                with open(configFileName, encoding="utf8") as fp:
                    # all lines
                    for cnt, line in enumerate(fp):
                        # remove blanks
                        line = line.strip()

                        # line to short
                        if (len(line) < startLength):
                            continue

                        # is it a comment line ?
                        if (line[0] == '/' and line[1] == '/'):
                            continue

                        # continue if '=' not in line:
                        idx = line.find('=')
                        if (idx < 0):
                            continue

                        # --------------------------------------------------------------------
                        # extract name
                        # --------------------------------------------------------------------

                        cfgName = line[startLength:idx].strip()

                        cfgText = line[idx + 1:-1].strip()

                        # Remove '' of texts
                        if (cfgText[0] == "'"):
                            cfgText = cfgText[1:-1]

                        # --- assign config item  ---------------------------------------------

                        self.__configurations[cfgName] = cfgText

        except Exception as ex:
            print(ex)

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
            print("Configurations: " + str(len(self.__configurations)))
            for key, value in self.__configurations.items():
                print("   " + key + " = '" + value + "'")

        except Exception as ex:
            print(ex)

    ##-------------------------------------------------------------------------------

    def asInt(self, cfgName):
        print('    >>> Enter asInt: ')
        print('cfgName: ' + cfgName)

        # negative max to indicate error
        #intValue = -sys.maxint - 1
        intValue = None

        try:

            if (cfgName in self.__configurations):

                strValue = self.__configurations [cfgName]
                intValue = int(strValue)

            else:
                print ('!!! warning value "%s" does not exist in configuration list !!!' % cfgName)

        except Exception as ex:
            print(ex)

        print ('       intValue: "' + intValue + '"')
        print('    >>> Exit asInt: ')
        return intValue

    ##-------------------------------------------------------------------------------

    def asFloat(self, cfgName):
        print('    >>> Enter asFloat: ')
        print('cfgName: ' + cfgName)

        # negative max to indicate error
        #floatValue = -sys.maxint - 1
        floatValue = None

        try:

            if (cfgName in self.__configurations):

                strValue = self.__configurations [cfgName]
                floatValue = float(strValue)

            else:
                print ('!!! warning value "%s" does not exist in configuration list !!!' % cfgName)

        except Exception as ex:
            print(ex)

        print ('       floatValue: "' + floatValue + '"')
        print('    >>> Exit asFloat: ')
        return floatValue

    ##-------------------------------------------------------------------------------

    def asText(self, cfgName):
        print('    >>> Enter asText: ')
        print('cfgName: ' + cfgName)

        strValue = None

        try:

            if (cfgName in self.__configurations):

                strValue = self.__configurations [cfgName]

            else:
                print ('!!! warning value "%s" does not exist in configuration list !!!' % cfgName)

        except Exception as ex:
            print(ex)

        print ('       strValue: "' + strValue + '"')
        print('    >>> Exit asText: ')
        return strValue

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

    optlist, args = getopt.getopt(sys.argv[1:], 'c12345h')

    configPathFileName = 'd:/xampp/htdocs/joomla4x/configuration.php'

    for i, j in optlist:
        if i == "-c":
            configPathFileName = j

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

    jConfigFile = jConfigFile(configPathFileName)
    jConfigFile.Text()

    print ('-----------------------------')

    print("dbtype ='%s'" % jConfigFile.asText ('dbtype'))
    print("host ='%s'" % jConfigFile.asText ('host'))
    print("user ='%s'" % jConfigFile.asText ('user'))
    print("password ='%s'" % jConfigFile.asText ('password'))
    print("db ='%s'" % jConfigFile.asText ('db'))
    print("dbtype ='%s'" % jConfigFile.asText ('dbtype'))
    print("list_limit ='%s'" % jConfigFile.asText ('list_limit'))


    print_end(start)

