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
class CustomerComment extends \common\models\customer\CustomerComment
{

    /**
     * 获取客户评价数量
     */
    public static function getCustomerCommentCount($customer_id)
    {
        $comment_count = self::find()->where(['customer_id' => $customer_id])->count();
        return $comment_count;
    }

    /**
     * 获取客户评价数量
     */
    public static function addUserSuggest($customer_id, $order_id, $customer_comment_phone, $customer_comment_content, $customer_comment_tag_ids, $customer_comment_level)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $customerComment = new CustomerComment;
            $customerComment->customer_id = $customer_id;
            $customerComment->order_id = $order_id;
            $customerComment->customer_comment_phone = $customer_comment_phone;
            $customerComment->customer_comment_content = $customer_comment_content;
            $customerComment->customer_comment_tag_ids = $customer_comment_tag_ids;
            $customerComment->customer_comment_level = $customer_comment_level;
            $customerComment->validate();
            $customerComment->save();
            $transaction->commit();
            return $customerComment;
        } catch (\Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

















}
