@ECHO OFF
REM Backups RSG2 database tables and RSG2 files
REM Set 01: FithDell5570

CLS

ECHO ------------------------------------------
ECHO Set 01: FithDell5570 (Main PC)

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla4x"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla4x_Sim3x"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3x"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3xNextRelease"

ECHO ------------------------------------------
CALL BackupRsg2.para.bat "d:\xampp\htdocs" "joomla3xRelease"

ECHO ------------------------------------------
