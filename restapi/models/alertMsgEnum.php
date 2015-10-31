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
    //用户登录
    const userLoginSuccess = '用户登录成功';
    const userLoginFailed = '用户认证已经过期,请重新登录';
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
    
    //阿姨登录
    const workerLoginSuccess = '登录成功';
    const workerLoginFailed = '用户认证已经过期,请重新登录';
    const workerLoginBossFailed = '登录失败';
    //阿姨申请请假
    const workerApplyLeaveTypeFailed = '请假类型不正确';
    const workerApplyLeaveTimeFailed = '请假时间不正确';
    const workerApplyLeaveFailed = '请假申请失败';
    const workerApplyLeaveSuccess = '您的请假已提交，请耐心等待审批';
    //获取阿姨请假历史记录
    const workerLeaveHistorySuccess = '获取阿姨请假历史记录成功';
    const workerLeaveHistoryFailed = '获取阿姨请假历史记录失败';
    //获取阿姨住址
    const workerLivePlaceSuccess = '获取阿姨住址成功';
    const workerLivePlaceFailed = '获取阿姨住址失败';
    //获取阿姨评论
    const workerCommentTypeFailed = '评论类型不正确';
    const workerCommentSuccess = '获取评论成功';  
    const workerCommentFailed = '获取评论失败';
    //获取阿姨投诉
    const workerComplainSuccess = '获取投诉成功';
    const workerComplainFailed = '获取投诉失败';
    //获取阿姨服务信息
    const workerServiceInfoSuccess = '获取服务信息成功';
    const workerServiceInfoFailed = '获取服务信息失败';
    //获取阿姨账单列表
    const workerBillListSuccess = '获取账单列表成功';
    const workerBillListFailed = '获取账单列表失败';
    //获取阿姨工时列表
    const workerSettleIdFailed = '账单唯一标识错误';
    const workerTasktimeListSuccess = '获取工时列表成功';
    const workerTasktimeListFailed = '获取工时列表失败';
    //获取阿姨任务奖励列表
    const workerTaskRewardListSuccess = '获取任务奖励成功';
    const workerTaskRewardListFailed = '获取任务奖励失败';
    //获取阿姨处罚列表
    const workerPunishListSuccess = '获取处罚列表成功';
    const workerPunishListFailed = '获取处罚列表失败';
    //账单确定
    const workerBillConfirmSuccess = '账单确认成功';
    const workerBillConfirmFailed = '账单确认失败';
    //阿姨用户中心
    const workerCenterSuccess = '获取阿姨数据成功';
    const workerCenterFailed = '获取阿姨数据失败';
    //阿姨信息
    const workerInfoSuccess = '获取阿姨信息成功';
    const workerInfoFailed = '获取阿姨信息失败';

}