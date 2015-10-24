<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_stat}}".
 *
 * @property integer $worker_id
 * @property integer $worker_stat_order_num
 * @property string $worker_stat_order_money
 * @property integer $worker_stat_order_refuse
 * @property integer $worker_stat_order_complaint
 * @property integer $worker_stat_sale_cards
 * @property integer $updated_ad
 */
class WorkerStat extends \common\models\worker\WorkerStat
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_stat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_stat_order_num', 'worker_stat_order_refuse', 'worker_stat_order_complaint', 'worker_stat_sale_cards', 'updated_ad'], 'integer'],
            [['worker_stat_order_money'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'worker_id' => Yii::t('app', '主表阿姨id'),
            'worker_stat_order_num' => Yii::t('app', '阿姨订单总数'),
            'worker_stat_order_money' => Yii::t('app', '阿姨订单总金额'),
            'worker_stat_order_refuse' => Yii::t('app', '阿姨拒绝订单数'),
            'worker_stat_order_complaint' => Yii::t('app', '阿姨接到投诉数'),
            'worker_stat_sale_cards' => Yii::t('app', '阿姨销售会员卡数量'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
        ];
    }
}
