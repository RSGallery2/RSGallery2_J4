@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python RestoreRsg2.py 

REM JoomlaPath
Call :AddNextArg -p "d:\xampp\htdocs"
REM Call :AddNextArg -p "e:\xampp_J2xJ3x\htdocs"

REM JoomlaName
REM Call :AddNextArg -n "joomla4x"
Call :AddNextArg -n "joomla4x_Sim3x"
REM Call :AddNextArg -n "joomla3x"
REM Call :AddNextArg -n "joomla25"

REM BackupPath
Call :AddNextArg -b "..\..\..\RSG2_Backup\joomla3x.20200430_171320"

REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python RestoreRsg2.py %CmdArgs% %* 
     python RestoreRsg2.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

