@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python findTranslationReferences.py 

REM 
REM Call :AddNextArg -p "..\..\admin"
                     
REM 
REM Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python findTranslationReferences.py %CmdArgs% %* 
     python findTranslationReferences.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

