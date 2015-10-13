<?php

namespace common\models;

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
class WorkerStat extends \yii\db\ActiveRecord
{

}
