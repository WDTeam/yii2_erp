:: ����˵��
::
:: �汾��V1.0
:: ���ڣ�2015-10-08
:: ˵����ʹ�ò˵����� redis client �ͻ��ˣ���ѡ�� local, dev, test, prod
::
@echo off
title �Ҽ��˵���������
echo.
echo �������Ϊ�����ļ����ļ��е��Ҽ��˵���ӡ��������С��˵�
 
:menu
echo.
echo 1.local(����) 2.dev(����) 3.test(����) 4.prod(����)
set /p choice=��ѡ�������
echo.
if /i "%choice%" == "1" goto local
if /i "%choice%" == "2" goto dev
if /i "%choice%" == "3" goto test
if /i "%choice%" == "4" goto prod
echo ������Ч��������ѡ��
goto menu

:local
redis-cli -p 6379
echo ����ɣ�
echo.
pause
goto exit
 
:dev
redis-cli -h 101.200.179.70 -p 6379
echo ����ɣ�
echo.
goto exit

:test
redis-cli -h 101.200.200.74 -p 6379
echo ����ɣ�
echo.
goto exit

:exit
:: �ָ����ڱ���
title %comspec%