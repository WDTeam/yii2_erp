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