<?php
namespace autoassign;
/*
 * BOSS 自动派单客户端命令 枚举类
 * @author 张航<zhanghang@1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
 */
class ClientCommand {
    const ALL_REDIS_ORDERS = 0;
    const START = 1;
    const STOP = 2;
    const RELOAD = 3;
    const UPDATE = 4;
}
