<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_comment}}".
 *
 * @property integer $id
 * @property string $order_id
 * @property string $customer_id
 * @property string $customer_comment_phone
 * @property string $customer_comment_content
 * @property integer $customer_comment_star_rate
 * @property integer $customer_comment_anonymous
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class CustomerComment extends \common\models\CustomerComment
{
    
    /**
     * 获取客户评价数量
     */
    public static function getCustomerCommentCount($customer_id){
        $comment_count = self::find()->where(['customer_id'=>$customer_id])->count();
        return $comment_count;
    }
}
