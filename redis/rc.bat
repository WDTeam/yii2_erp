:: 更新说明
::
:: 版本：V1.0
:: 日期：2015-10-08
:: 说明：使用菜单启动 redis client 客户端，可选择 local, dev, test, prod
::
@echo off
title 右键菜单打开命令行
echo.
echo 本程序可为任意文件及文件夹的右键菜单添加“打开命令行”菜单
 
:menu
echo.
echo 1.local(本机) 2.dev(开发) 3.test(测试) 4.prod(生产)
set /p choice=请选择操作：
echo.
if /i "%choice%" == "1" goto local
if /i "%choice%" == "2" goto dev
if /i "%choice%" == "3" goto test
if /i "%choice%" == "4" goto prod
echo 输入无效，请重新选择！
goto menu

:local
redis-cli -p 6379
echo 已完成！
echo.
pause
goto exit
 
:dev
redis-cli -h 101.200.179.70 -p 6379
echo 已完成！
echo.
goto exit

:test
redis-cli -h 101.200.200.74 -p 6379
echo 已完成！
echo.
goto exit

:exit
:: 恢复窗口标题
title %comspec%