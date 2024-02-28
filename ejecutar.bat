@echo off
set PORT=8000
set FOUND=0

REM Verificar si el puerto está en uso
for /f "tokens=5" %%a in ('netstat -aon ^| findstr /R /C:":%PORT% .* LISTENING"') do (
    set /a FOUND=1
    REM Finalizar el proceso que está usando el puerto
    taskkill /F /PID %%a
    REM Esperar un momento para asegurarse de que el proceso se haya cerrado
    timeout /t 5
)

cd /d C:\MRMBACK

REM Iniciar el servicio PHP
if %FOUND%==1 (
    echo El servicio en el puerto %PORT% fue reiniciado.
    echo ALERTA: El servicio PHP en el puerto %PORT% ha sido reiniciado.
) else (
    echo Iniciando el servicio en el puerto %PORT%.
)
php -S localhost:%PORT% -t public
