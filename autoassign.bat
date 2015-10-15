@echo off

rem -------------------------------------------------------------
rem  BOSS 自动派单服务
rem
rem  @author 张航 <zhanghang@1jiajie.com>
rem  @author 张旭刚<zhangxugang@corp.1jiajie.com>
rem  @link http://boss.1jiajie.com/
rem  @copyright Copyright (c) 2015 E家洁 LLC
rem -------------------------------------------------------------

@setlocal

set YII_PATH=%~dp0
set AUTO_ASSIGN_PATH=%YII_PATH%autoassign
set ASSIGN_COMMAND=autoassign.php

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%AUTO_ASSIGN_PATH%\%ASSIGN_COMMAND%" %*

@endlocal
