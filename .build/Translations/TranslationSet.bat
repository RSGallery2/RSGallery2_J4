@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python TranslationSet.py 

REM directory
Call :AddNextArg -d "..\\..\\admin\language\"

REM type "ini"or "sys.ini" (File ending for filenames *.type
Call :AddNextArg -t "ini"
REM Call :AddNextArg -t "sys.ini"

REM 
REM Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python TranslationSet.py %CmdArgs% %* 
     python TranslationSet.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

