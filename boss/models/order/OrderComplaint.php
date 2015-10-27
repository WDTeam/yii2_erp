<?php

namespace boss\models\order;

use Yii;
use common\models\order\OrderExtCustomer;
use common\models\order\OrderExtWorker;
/**
 * This is the model class for table "ejj_order_complaint".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $complaint_type
 * @property integer $complaint_status
 * @property integer $complaint_channel
 * @property integer $complaint_section
 * @property string $complaint_level
 * @property string $complaint_phone
 * @property string $complaint_content
 * @property integer $complaint_time
 */
class OrderComplaint extends \common\models\order\OrderComplaint
{
    
}
