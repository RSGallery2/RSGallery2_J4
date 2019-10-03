@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python TranslationFile.py 

REM 
Call :AddNextArg -l "..\\..\\admin\language\en-GB\en-GB.com_rsgallery2.ini"
                     
REM 
REM Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python TranslationFile.py %CmdArgs% %* 
     python TranslationFile.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

