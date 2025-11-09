@echo off
cd /d C:\apache\htdocs\AppSalon\public
start /min "" php -S localhost:3000
start "" http://localhost:3000
start "" https://www.udemy.com/course/desarrollo-web-completo-con-html5-css3-js-php-y-mysql/learn
exit