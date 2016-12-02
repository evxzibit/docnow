@echo off
REM Note: this file must be run as Administrator
REM --------------------------------------------
set /p phpPath=Enter the path to your php.exe file (e.g. C:\php5\php.exe): 
@echo off
assoc .php=phpfile
ftype phpfile="%phpPath%" -q -f "%%1" -- %%~2
echo All done!