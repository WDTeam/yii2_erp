<?php

namespace dbbase\models\customer;

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
class CustomerComment extends \yii\db\ActiveRecord
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
            [['order_id','operation_shop_district_id', 'customer_id', 'customer_comment_phone'], 'required'],
            #郝建设修改，去掉了'customer_comment_star_rate' 字段，和数据库字段不符合。不能正常insert用户评论
            # [['order_id', 'customer_id', 'customer_comment_star_rate', 'customer_comment_anonymous', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['order_id','province_id','city_id','county_id','worker_id','customer_id', 'customer_comment_anonymous', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_phone','worker_tel'], 'string', 'max' => 11],
            [['customer_comment_content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public $created_at_end;
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'order_id' => Yii::t('boss', '订单'),
            'customer_id' => Yii::t('boss', '客户'),
            'customer_comment_phone' => Yii::t('boss', '用户电话'),
            'customer_comment_level_name' => Yii::t('boss', '等级'),
            'customer_comment_content' => Yii::t('boss', '评价备注'),
       		 'customer_comment_tag_names' => Yii::t('boss', '标签'),
             'customer_comment_level' => Yii::t('boss', '等级'),
            'customer_comment_anonymous' => Yii::t('boss', '是否匿名评价,0匿名,1非匿名'),
            'operation_shop_district_id' => Yii::t('boss', '商圈'),
            'province_id' => Yii::t('boss', '省份'),
            'city_id' => Yii::t('boss', '城市'),
            'county_id' => Yii::t('boss', '地区'),
            'worker_tel' => Yii::t('boss', '阿姨电话'),
            'worker_id' => Yii::t('boss', '阿姨'),
            'created_at' => Yii::t('boss', '评价时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'created_at_end' => Yii::t('boss', '结束时间'),
            'is_del' => Yii::t('boss', '删除'),
        ];
    }

}
