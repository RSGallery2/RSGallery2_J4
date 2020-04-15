@ECHO OFF
REM Imports dump of RSG2 tables from given database

CLS

Set CmdArgs=
ECHO python Rsg2TablesImportDump.py

REM database
Call :AddNextArg -d ""

REM password
Call :AddNextArg -p ""

REM user
Call :AddNextArg -u ""

REM dumpFileName
Call :AddNextArg -f ""

REM
REM Call :AddNextArg -p ""

REM add command line
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python Rsg2TablesImportDump.py %CmdArgs% %*
     python Rsg2TablesDump.py %CmdArgs% %*

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

