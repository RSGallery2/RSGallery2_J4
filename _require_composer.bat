@ECHO OFF
REM _require_composer.bat
REM
CLS

REM Path for calling
set ExePath=e:\wamp64\bin\php\php8.4.5\
REM ECHO ExePath: "%ExePath%"

if exist "%ExePath%php.exe" (
    REM path known (WT)
    ECHO ExePath: "%ExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    set ExePath=
)

"%ExePath%php.exe" --version

ECHO ----------------------------------------------
ECHO.

echo --- composer require --dev rector/rector joomla-projects/jrector joomla-projects/typehints
composer require --dev rector/rector joomla-projects/jrector joomla-projects/typehints


ECHO.
ECHO Now:
ECHO copy vendor\joomla-projects\jrector\assets\rector.php .\
ECHO.
GOTO :EOF


REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

