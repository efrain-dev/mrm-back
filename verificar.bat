@echo off
set PORT=8000
set FOUND=0

REM Verificar si el puerto está en uso
for /f "tokens=5" %%a in ('netstat -aon ^| findstr /R /C:":%PORT% .* LISTENING"') do (
    set /a FOUND=1
)

cd /d C:\MRMBACK

REM Iniciar el servicio PHP solo si no se encontró en ejecución
if %FOUND%==0 (
    echo Iniciando el servicio en el puerto %PORT%.
    php -S localhost:%PORT% -t public
) else (
    echo El servicio ya está en ejecución en el puerto %PORT%.
)