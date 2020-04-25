@ECHO OFF
REM Backups RSG2 database tables and RSG2 files

CLS

Set CmdArgs=
ECHO python BackupRsg2.py 

REM JoomlaPath
Call :AddNextArg -p "d:\xampp\htdocs"
                     
REM JoomlaName
Call :AddNextArg -n "joomla4x"
REM Call :AddNextArg -n "joomla3x"
 
REM BackupPath
Call :AddNextArg -b "..\..\..\RSG2_Backup"

REM add command line
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python BackupRsg2.py %CmdArgs% %* 
     python BackupRsg2.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

