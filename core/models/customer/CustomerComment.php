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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'customer_comment_phone'], 'required'],
            [['order_id', 'customer_id', 'customer_comment_star_rate', 'customer_comment_anonymous', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_phone'], 'string', 'max' => 11],
            [['customer_comment_content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'order_id' => Yii::t('boss', '订单ID'),
            'customer_id' => Yii::t('boss', '用户ID'),
            'customer_comment_phone' => Yii::t('boss', '用户电话'),
            'customer_comment_content' => Yii::t('boss', '评论内容'),
            'customer_comment_star_rate' => Yii::t('boss', '评论星级,0为评价,1-5星'),
            'customer_comment_anonymous' => Yii::t('boss', '是否匿名评价,0匿名,1非匿名'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '删除'),
        ];
    }

    /**
     * 获取客户评价数量
     */
    public static function getCustomerCommentCount($customer_id){
        $commentCount = self::find()->where(['customer_id'=>$customer_id])->count();
        return $commentCount;
    }
}
