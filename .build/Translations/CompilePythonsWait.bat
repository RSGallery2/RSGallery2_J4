@ECHO OFF
REM Compiles all *.py file in directory

python -V

for /f %%f in ('dir /b *.py') do ( 
	REM echo.
	echo --- %%f
	python -m py_compile %%f
	if errorlevel 1 Call :ErrAtRegSvr %%f
	
)
REM --- exit ----------------------
goto :EOF

REM ------------------------------------------
REM Print an error message
:ErrAtRegSvr
	@ECHO OFF
	Echo.
	ECHO !!! Please fix error at %1" !!!
	ECHO %time%
	Echo.
	PAUSE

	echo    * %1
	python -m py_compile %1
	
	if errorlevel 1 goto :ErrAtRegSvr
	
REM @ECHO ON
goto :EOF

RESTART:

