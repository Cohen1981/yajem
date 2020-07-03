IF [%1] == [] (
echo build for only zip; copy for only copy; deploy for zip and copy
goto END
)

IF [%1] == [build] (
call :BUILD
goto END
)

if [%1] == [copy] (
call :COPY
goto END
)

if [%1] == [deploy] (
call :BUILD
call :COPY
goto END
)

:BUILD
docker exec -it joomla_cms /vendor/bin/phing -f /resources/build/build.xml
goto :eof

:COPY
xcopy /E /Y .\src\sassFiles\user.css .\src\templates\survivants\css\
xcopy /E /Y .\src\sassFiles\popup.css .\src\templates\survivants\css\
xcopy /E /Y .\src\sassFiles\sdajem_style.css .\src\media\com_sdajem\css\
xcopy /E /Y .\src\sassFiles\sdaprofiles_style.css .\src\media\com_sdaprofiles\css\
xcopy /E /Y .\src\sassFiles\sdacontacts_style.css .\src\media\com_sdacontacts\css\
xcopy /E /Y .\src .\.ContainerData\www-data\
goto :eof

:END