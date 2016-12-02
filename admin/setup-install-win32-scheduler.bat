@echo off
REM Note: this file must be run as Administrator
REM --------------------------------------------
set /p phpPath=Enter the path to your php.exe file (e.g. C:\php5\php.exe): 
@echo off
echo Creating service Ensight Scheduler Win32...
sc create ensight-scheduler binPath= "%phpPath% %CD%\scheduler.php --start" DisplayName= "Ensight Scheduler Win32" start= "auto"
sc start ensight-scheduler
echo All done!