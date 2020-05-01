@ECHO OFF
REM Exports dump of RSG2 tables from given database
REM Configuration and pathes will be taken from configuration.php of referenced joomla installation

CLS

Set CmdArgs=
ECHO python Rsg2TablesBackup.py 

REM JoomlaPath
Call :AddNextArg -p "d:\xampp\htdocs"
REM Call :AddNextArg -p "e:\xampp_J2xJ3x\htdocs"

REM JoomlaName
REM Call :AddNextArg -n "joomla4x"
Call :AddNextArg -n "joomla4x"
REM Call :AddNextArg -n "joomla3x"
REM Call :AddNextArg -n "joomla25"

REM dumpFileName
Call :AddNextArg -f "..\..\..\RSG2_Backup\\joomla3x.20200430_171320\Rsg2_TablesDump.j3x.ttt1.sql"

REM
REM Call :AddNextArg -p ""

REM add command line
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python Rsg2TablesBackup.py %CmdArgs% %* 
     python Rsg2TablesBackup.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

