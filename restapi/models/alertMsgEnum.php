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
    const balancePayFailed='余额支付失败';
    //在线支付
    const onlinePaySuccess='在线支付成功';
    const onlinePayFailed='在线支付失败';

    //获取城市全部上线服务
    const allServicesSuccess='获取城市上线服务成功';
    const allServicesFailed='获取城市上线服务失败';
    //用户端首页初始化
    const getUserInitSuccess='获取用户端初始化信息成功';
    const getUserInitFailed='获取用户端初始化信息失败';
    //阿姨端首页初始化
    const getWorkerInitSuccess='获取阿姨端初始化信息成功';
    const getWorkerInitFailed='获取阿姨端初始化信息失败';
    //阿姨启动页
    const getWorkerStartPageSuccess='获取阿姨端启动页成功';
    const getWorkerStartPageFailed='获取阿姨端启动页失败';

    //获取用户信息
    const getUserInfoSuccess='获取用户信息成功';
    const getUserInfoFailed='获取用户信息失败';

    //阿姨登录
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
    const workerSettleIdFailed = '账单唯一标识错误。';
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
    
    
    /*
     * 李勇begin
     */
    //通用系统错误提示
    const bossError = 'boss系统错误';
    //通用用户登录已过期
    const customerLoginFailed = '用户认证已经过期,请重新登录';
    
    //用户登录
    //用户登录成功
    const customerLoginSuccess = '登陆成功';
    //用户登录失败
    const customerLoginFail = '登陆失败';
    //用户登录手机号或验证码不能为空
    const customerLoginDataDefect = '用户名或验证码不能为空';

    //阿姨登录
    //阿姨登录成功
    const workerLoginSuccess = '登陆成功';
    //阿姨登录失败
    const workerLoginFail = '登陆失败';
    //阿姨登录手机号或验证码不能为空
    const workerLoginDataDefect = '用户名或验证码不能为空';
    //阿姨登录手机号格式不对
    const workerLoginWrongPhoneNumber = '请输入正确手机号';
    //没有此阿姨
    const workerLoginNoWorker = '没有此阿姨，请联系客服';
    //阿姨已在黑名单
    const workerLoginIsBlackList = '阿姨已在黑名单';
    //阿姨已离职
    const workerLoginIsDimission = '阿姨已离职';
    //阿姨已删除
    const workerLoginIsDel = '阿姨已删除';
    
    //优惠码
    //优惠码或手机号不能为空
    const exchangeCouponDataDefect = '优惠码或手机号不能为空';
    //兑换优惠券成功
    const exchangeCouponSuccess = '兑换成功';
    //兑换优惠券失败
    const exchangeCouponFail = '兑换失败';
    
    //可用优惠券列表
    //请选择城市
    const couponsCityNoChoice = '请选择城市';
    //获取优惠券列表成功
    const couponsSuccess = '获取优惠券列表成功';
    //优惠券列表为空
    const couponsFail = '优惠券列表为空';
    
    //获取用户优惠券列表（包括该城市可用的、还有过期30天内的优惠券）
    //请选择城市
    const couponsOverDueNoChoice = '请选择城市';
    //获取优惠券列表成功
    const couponsOverDueSuccess = '获取优惠券列表成功';
    //优惠券列表为空
    const couponsOverDueFail = '优惠券列表为空';
    
    //用户全部优惠券列表
    //获取优惠券列表成功
    const allCouponsSuccess = '获取优惠券列表成功';
    //优惠券列表为空
    const allCouponsFail = '优惠券列表为空';
    
    //用户优惠券数量
    //获取用户优惠券数量成功
    const getCouponCountSuccess = '获取用户优惠券数量成功';
    
    //单次服务排班表
    //请填写服务地址或服务时长
    const singleServiceTimeDataDefect = '请填写服务地址或服务时长';
    //商圈不存在
    const singleServiceTimeDistrictNotExist = '商圈不存在';
    //获取单次服务排班表成功
    const singleServiceTimeSuccess = '获取单次服务排班表成功';
    
    //周期服务排班表
    //请填写服务地址或服务时长或选择阿姨
    const recursiveServiceTimeDataDefect = '请填写服务地址或服务时长或选择阿姨';
    //商圈不存在
    const recursiveServiceTimeDistrictNotExist = '商圈不存在';
    //获取单次服务排班表成功
    const recursiveServiceTimeSuccess = '获取周期服务时间表成功';
    
    //周期服务可用阿姨列表
    //请填写服务地址
    const serverWorkerListNoAddress = '请填写服务地址';
    //请输入分页每页条数和第几页
    const serverWorkerListNoPage = '请输入分页每页条数和第几页';
    //没有可用阿姨
    const serverWorkerListFail = '没有可用阿姨';
    //获取周期服务可用阿姨列表成功
    const serverWorkerListSuccess = '获取周期服务可用阿姨列表成功';
    //商圈不存在
    const serverWorkerListDistrictNotExist = '商圈不存在';
    
    //选择周期服务的第一次服务日期列表
    //请选择服务时长或阿姨
    const firstServiceTimeNoWorker = '请选择服务时长或阿姨';
    //请选择预约时间段
    const firstServiceTimeNoTime = '请选择预约时间段';
    //查询第一次服务日期列表失败
    const firstServiceTimeFail = '查询第一次服务日期列表失败';
    //获取周期服务可用阿姨列表成功
    const firstServiceTimeSuccess = '获取周期服务可用阿姨列表成功';
    
    //查看请假情况
    //请选择请假类型
    const workerLeaveNoType = '请选择请假类型';
    //获取阿姨请假排班表成功
    const workerLeaveSuccess = '获取阿姨请假排班表成功';
    
    //获得进行中的任务列表
    //您没有任务哦
    const taskDoingFail = '您没有任务哦';
    //操作成功
    const taskDoingSuccess = '操作成功';
    
    //获得已完成的任务列表
    //数据不完整,请输入每页条数和第几页
    const taskDoneNoPage = '数据不完整,请输入每页条数和第几页';
    //您没有已完成任务哦
    const taskDoneFail = '您没有已完成任务哦';
    //操作成功
    const taskDoneSuccess = '操作成功';
    
    //获得已失败的任务列表
    //数据不完整,请输入每页条数和第几页
    const taskFailNoPage = '数据不完整,请输入每页条数和第几页';
    //您没有已完成任务哦
    const taskFailFail = '您没有任务哦';
    //操作成功
    const taskFailSuccess = '操作成功';
    
    //查看任务的详情
    //您没有已完成任务哦
    const checkTaskFail = '查看任务失败';
    //操作成功
    const checkTaskSuccess = '操作成功';
    //请填写任务id
    const checkTaskNoId = '请填写任务id';
    
    //用户发送验证码
    //短信发送失败
    const sendWorkerCodeSuccess = '验证码已发送手机，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636';
    //短信发送成功
    const sendWorkerCodeFaile = '验证码已发失败';
    /*
     * 李勇end
     */
}