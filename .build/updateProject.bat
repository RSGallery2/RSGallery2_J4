@echo off
CLS

ECHO updateProject.bat
REM Tell  about it ...

REM copy original to smash it later
REM copy ..\rsgallery2.org.xml ..\rsgallery2.xml

REM ECHO phing -verbose -debug -logfile .\updateProject.log -f updateProject.xml
REM phing -verbose -debug -logfile .\updateProject.log -f updateProject.xml
REM phing -logfile .\updateProject.log -f updateProject.xml
REM ECHO phing -logfile .\updateProject.log -f updateProject.xml
REM call phing -logfile .\updateProject.log -f updateProject.xml
ECHO phing -f updateProject.xml
call phing -f updateProject.xml

ECHO ------------------------------------
REM TYPE .\updateProject.log
REM ECHO ------------------------------------
