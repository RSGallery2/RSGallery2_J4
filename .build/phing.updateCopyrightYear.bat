@ECHO off
REM Starts Target "Improve php file "since" doc parts: add version * @since  x.y.z"

REM phing -verbose -debug -logfile .\build.log .\build.xml
REM phing -verbose -logfile .\since.log .\build.xml
REM phing -logfile .\build.log .\build.xml

REM -longtargets
REM -
phing -logfile .\UpdateCopyrightYear.log -f .\updateProject.xml UpdateCopyrightYear




