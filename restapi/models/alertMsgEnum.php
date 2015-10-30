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
    //赵顺利
    //用户发送验证码
    const sendUserCodeSuccess = '验证码已发送手机，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636';
    const sendUserCodeFailed = '验证码已发失败';
    //依据城市和服务品类 获取服务商品列表
    const getGoodsesSuccess='获取服务商品成功';
    const getGoodsesFailed='获取服务商品失败';
    //依据城市 获取首页服务商品列表
    const homeGoodsesSuccess='获取首页服务商品成功';
    const homeGoodsesFailed='获取首页服务商品失败';
    //依据城市 获取所有服务商品列表
    const allGoodsesSuccess='获取服务商品成功';
    const allGoodsesFailed='获取服务商品失败';
    //获取某城市某商品的价格及备注信息
    const goodsInfoSuccess='获取服务商品成功';
    const goodsInfoFailed='获取服务商品失败';
    //获得所有保洁任务项目
    const allCleaningTaskSuccess='获取所以保洁任务成功';
    const allCleaningTaskFailed='获取所以保洁任务失败';
    //根据地址获取百度地图数据
    const baiduMapSuccess='获取百度地图数据成功';
    const baiduMapFailed='获取百度地图数据失败';

    //会员余额支付
    const balancePaySuccess='余额支付成功';

}