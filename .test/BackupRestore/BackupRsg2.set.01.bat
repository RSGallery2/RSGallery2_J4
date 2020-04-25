@ECHO OFF
REM Backups RSG2 database tables and RSG2 files

CLS

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla4x"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3x"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3xNextRelease"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3xRelease"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla4xOld"

ECHO ------------------------------------------