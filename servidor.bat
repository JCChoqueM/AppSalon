@echo off
cd /d C:\apache\htdocs\AppSalon\public
start /min "" php -S localhost:3000
start "" http://localhost:3000/admin
exit