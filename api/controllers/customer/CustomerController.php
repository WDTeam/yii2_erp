<?php
namespace api\controllers\customer;

use Yii;
use core\models\customer\CustomerAccessToken;
use core\models\customer\CustomerComment;
use core\models\comment\CustomerCommentLevel;
use core\models\comment\CustomerCommentTag;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends \api\components\Controller
{
	/**
	* @apiError UserNotFound 用户认证已经过期.
	*
	* @apiErrorExample Error-Response:
	*     HTTP/1.1 403 Not Found
	*     {
	*       "code": "0",
	*       "msg": "用户认证已经过期,请重新登录，"
	*
	*     }
	**/
	
	 //public function beforeAction($param){
	 	
	 //echo  '111111'; exit;
      // 	 	parent::beforeAction($param);
	 /* 	if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
			return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
		}  */
	 		
	//} 
	
	
	
    /**
     * @api {POST} v1/user/user-suggest 用户评价 
     *
     * @apiName UserSuggest
     * @apiGroup User
     * @apiParam {int} order_id       '订单ID'
     * @apiParam {int} customer_id     '用户ID'
	 * @apiParam {int} worker_id      '阿姨id'
	 * @apiParam {String} worker_tel  '阿姨电话'
	 * @apiParam {int} operation_shop_district_id '商圈id'
	 * @apiParam {int} province_id    '省id'
	 * @apiParam {int} city_id        '市id'
	 * @apiParam {int} county_id      '区id'
	 * @apiParam {String} customer_comment_phone    '用户电话'
	 * @apiParam {String} customer_comment_content  '评论内容'
	 * @apiParam {int} customer_comment_level       '评论等级'
	 * @apiParam {String} customer_comment_level_name '评价等级名称'
	 * @apiParam {String} customer_comment_tag_ids  '评价标签'
	 * @apiParam {String} customer_comment_tag_names '评价标签名称'
	 * @apiParam {int} customer_comment_anonymous  是否匿名评价,0匿名,1非匿名'
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "用户评价提交成功"
     *
     *     }
     *
     */
	

	
	
    public function actionUserSuggest()
    {
    	
    	/*
    	 * 测试代码成功
    	 *  $param['order_id']='12345566';
    	$param['customer_id']='888';
    	$param['worker_id']='4';
    	$param['worker_tel']='15172543897';
    	$param['operation_shop_district_id']='4';
    	$param['province_id']='1401';
    	$param['city_id']='140101';
    	$param['county_id']='14010101';
    	$param['customer_comment_phone']='15177432345';
    	$param['customer_comment_content']='这个阿姨有点颠';
    	$param['customer_comment_level']='1';
    	$param['customer_comment_level_name']='满意';
    	$param['customer_comment_tag_ids']='1,5';
    	$param['customer_comment_tag_names']='热情大方 服务态度好';
    	$param['customer_comment_anonymous']=1;
    	$model = CustomerComment::addUserSuggest($param);
    	return $this->send([1], "添加评论成功"); exit; */
    	
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	
    	$customer = CustomerAccessToken::getCustomer($param['access_token']);
    
    	if (!empty($customer) && !empty($customer->id)) {
    		$param['id']=$customer->id;
    		$model = CustomerComment::addUserSuggest($param);
    		
    		/* 接口地址：/order/order-complaint/app
    		接口参数：
    		字段说明：
    		主键id: id
    		订单Id:order_id     int(11)
    		阿姨Id:worker_id   int(11)
    		投诉类型：complaint_type  tinyint(2) 订单投诉(1)  非订单投诉(0)
    		投诉状态：complaint_status  tinyint(2)
    		投诉渠道：complaint_channel tinyint(2)
    		投诉电话：complaint_phone int(11)
    		投诉部门：complaint_section      tinyint(2)
    		投诉级别：complaint_level            char(2)
    		投诉详情：complaint_content      varchar(255)
    		创建时间：complaint_time int  (11)
    		你把order_id，complaint_content这两项是必选项 */
    		
    		
    		
    		if (!empty($model)) {
    			if($param['customer_comment_level']=='3'){
    			//如果是差评 通知投诉接口	
						$data['order_id']=$param['order_id'];
						$data['worker_id']=$param['worker_id'];
						$data['complaint_type']=1;
						$data['complaint_status']=0;
						$data['complaint_channel']=0;
						$data['complaint_phone']=$param['worker_tel'];
						$data['complaint_section']=0;
						$data['complaint_level']=3;
						$data['complaint_content']=$param['customer_comment_content'];
						$data['complaint_time']=time();
						//提交给投诉接口
						$customer::add($data);
    			}
    			return $this->send([1], "添加评论成功");
    		} else {
    			return $this->send(null, "添加评论失败", 0, 403);
    		}
    	} else {
    		return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
    	}
    }
    
    
    
    /**
     * @api {GET} v1/user/get-comment-level 获取用户评价等级
     *
     * @apiName GetCommentLevel
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取评论级别成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_comment_level": "级别代号",
     *          "customer_comment_level_name": "级别名称",
     *          "is_del": "是否删除",
     *
     *           }
     */
    
    public function actionGetCommentLevel()
    {
    	
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	$customer = CustomerAccessToken::getCustomer($param['access_token']);
    
    	if (!empty($customer) && !empty($customer->id)) {
    
    		$level = CustomerCommentLevel::getCommentLevel();
    		if (!empty($level)) {
    			$ret = ['comment' => $level];
    			return $this->send($ret, "获取评论级别成功");
    		} else {
    			return $this->send(null, "获取评论级别失败", 0, 403);
    		}
    	} else {
    		return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
    	}
    }
        
    
    /**
     * @api {GET} v1/user/get-comment-level-tag 获取用户评价等级下面的标签
     *
     * @apiName GetCommentLevelTag
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} customer_comment_level 级别id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取评论标签成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_tag_name": "评价标签名称",
     *          "customer_comment_level": "评价等级",
     *          "is_online": "是否上线",
     *          "is_del": "删除",
     *
     *           }
     *
     *
     */
    
    public function actionGetCommentLevelTag()
    {
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	
    		$level = CustomerCommentTag::getCommentTag($param['customer_comment_level']);
    		
    		if (!empty($level)) {
    			$ret = ['commentTag' => $level];
    			return $this->send($ret, "获取评论标签成功");
    		} else {
    			return $this->send(null, "获取评论标签失败", 0, 403);
    		}
    	
    }
    
    
    /**
    *  获取客户评价数量
    * @date: 2015-10-27
    * @author: peak pan
    * @return:
    **/
    
    
    
    public function actiongetcommentcount()
    {
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	$level = CustomerComment::getCustomerCommentCount($param['customer_id']);
    	if (!empty($level)) {
    		$ret = ['commentTag'=>true];
    		return $level;
    	}
    
    }
    
    
    
    
    /**
    * 根据评价的order_id获取评价详情
    * @date: 2015-10-27
    * @author: peak pan
    * @return:
    **/
    
    
    public function actionPostControllerIssetinfo()
    {
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	$level = CustomerComment::issetes($param['order_id']);
    	if (!empty($level)) {
    		$ret = ['data' => $level,'commentTag'=>true];
    		return $this->send($ret, '此订单已经评价');
    	} else {
    		return $this->send($ret, '此订单可以评价', 1, 200);
    	}
    	 
    }
    

    /**
    * 根据阿姨的id获取记录列表
    * @date: 2015-10-27
    * @author: peak pan
    * @return:
    **/
    public function actionPostControllerworkerlist()
    {
    	$param = Yii::$app->request->post();
    	if (empty($param)) {
    		$param = json_decode(Yii::$app->request->getRawBody(), true);
    	}
    	$level = CustomerComment::getCustomerCommentworkerlist($param['worker_id']);
    	if (!empty($level)) {
    		$ret = ['comment' => $level];
    		return $this->send($ret, '阿姨评价列表');
    		
    	} else {
    		return $this->send($ret, '暂无数据', 1, 200);
    	}
    
    }
    
    
     
    
    
}
