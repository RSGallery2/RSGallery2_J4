@ECHO OFF
REM <What it does>

CLS

Set CmdArgs=
ECHO python jConfigFile.py 

REM config file
Call :AddNextArg -c "d:/xampp/htdocs/joomla4x/configuration.php"
                     
REM 
REM Call :AddNextArg -n "modules"
 
REM add command line 
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO python jConfigFile.py %CmdArgs% %* 
     python jConfigFile.py %CmdArgs% %* 

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg 
Set NextArg=%*
Set CmdArgs=%CmdArgs% %NextArg%
ECHO  '%NextArg%'
GOTO :EOF

