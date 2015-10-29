# 
#  BOSS 自动派单运行服务热启动脚本
#  @author 张航<zhanghang@1jiajie.com>
#  @link http://boss.1jiajie.com/auto-assign/
#  @copyright Copyright (c) 2015 E家洁 LLC
#
echo "Reloading..."
cmd=$(pidof autoassign-server)

kill -USR1 "$cmd"
echo "Reloaded"