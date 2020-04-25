@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python jRsg2Tables.py 

REM database
Call :AddNextArg -d ""

REM password
Call :AddNextArg -p ""

REM user
Call :AddNextArg -u ""

REM dumpFileName
Call :AddNextArg -f ""

REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python jRsg2Tables.py %CmdArgs% %* 
     python jRsg2Tables.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

