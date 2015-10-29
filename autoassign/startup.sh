# 
#  BOSS 自动派单运行服务启动脚本
#  使用说明: 自动启动 在linux系统的 crontab 中加入： */1 * * * * ./startup.sh
#  @author 张航<zhanghang@1jiajie.com>
#  @link http://boss.1jiajie.com/auto-assign/
#  @copyright Copyright (c) 2015 E家洁 LLC
#
count=`ps -fe |grep "autoassign-server" | grep -v "grep" | wc -l`

echo $count
if [ $count -lt 1 ]; then
ps -eaf |grep "autoassign-server" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
php ./autoassign-server.php
echo "restart";
echo $(date +%Y-%m-%d_%H:%M:%S) >./restart.log
fi