@echo off
echo Starting XAMPP MySQL and Apache...
cd /d C:\xampp
start /min "" "C:\xampp\mysql_start.bat"
timeout /t 3 /nobreak >nul
start /min "" "C:\xampp\apache_start.bat"
echo.
echo Services starting... Please wait 10 seconds
timeout /t 10 /nobreak
echo.
echo Done! MySQL and Apache should now be running.
echo.
pause
