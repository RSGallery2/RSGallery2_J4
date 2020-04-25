@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python jRsg2Config.py 

REM database
Call :AddNextArg -d ""

REM database prefix
REM Call :AddNextArg -e "j4x_"
Call :AddNextArg -e "j4_"

REM password
Call :AddNextArg -p ""

REM user
Call :AddNextArg -u ""

REM dumpFileName
Call :AddNextArg -f ""

REM isUseJ3xTables
Call :AddNextArg -j ""


REM 
Call :AddNextArg -p "\pr004\entwickl\Schleif.nt"
                     
REM 
Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python jRsg2Config.py %CmdArgs% %* 
     python jRsg2Config.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

