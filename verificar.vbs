Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\MRMBACK\verificar.bat" & Chr(34), 0
Set WshShell = Nothing