@ECHO OFF
REM run actual rules of rector.php

ECHO vendor\bin\rector --dry-run
vendor\bin\rector --dry-run
