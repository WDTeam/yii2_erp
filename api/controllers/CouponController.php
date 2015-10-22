<?php
/**
 * Created by PhpStorm.
 * User: xieyi
 * Date: 15/10/22
 * Time: 上午12:27
 */
/**
 * 获取所有优惠劵by 城市或者通用 按到期时间，金额排序
 */

/**
 *
 * @api {GET} /user/exchangecoupon 兑换优惠劵
 *
 * @apiName ExchangeCoupon
 * @apiGroup User
 *
 * @apiParam {String} access_token 用户认证
 * @apiParam {String} city 城市
 * @apiParam {String} coupon_code 优惠码
 * @apiParam {String} app_version 访问源(android_4.2.2)
 *
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "code": "ok",
 *       "msg": "兑换成功"
 *
 *
 *     }
 *
 * @apiError UserNotFound 用户认证已经过期.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 403 Not Found
 *     {
 *       "code": "error",
 *       "msg": "用户认证已经过期,请重新登录，"
 *
 *     }
 *
 * @apiError CouponNotFound 优惠码不存在.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 403 Not Found
 *     {
 *       "code": "error",
 *       "msg": "优惠码不存在，"
 *
 *     }
 */
public function actionExchangeCoupon()
{

}