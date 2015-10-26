count=`ps -fe |grep "server.php" | grep -v "grep" | grep "master" | wc -l`

echo $count
if [ $count -lt 1 ]; then
ps -eaf |grep "server.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
php ./server.php
echo "restart";
echo $(date +%Y-%m-%d_%H:%M:%S) >./restart.log
fi