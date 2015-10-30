<?php
/**
 * Created by PhpStorm.
 * User: ejiajie
 * Date: 2015/10/30
 * Time: 18:11
 */

namespace restapi\models;


class alertMsgEnum
{
    //客户端提示信息文案
    const __default = '查询成功';

    const sendUserCodeSuccess = '验证码已发送手机，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636';
    const sendUserCodeFailed = '验证码已发失败';

}