<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationBootPage;

/**
 * OperationCitySearch represents the model behind the search form about `common\models\OperationCity`.
 */
class OperationBootPage extends CoreOperationBootPage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_boot_page_residence_time', 'created_at', 'updated_at'], 'integer'],
            [['operation_boot_page_name'], 'string', 'max' => 60],
            [['operation_boot_page_name'], 'required'],
            [['operation_boot_page_ios_img', 'operation_boot_page_android_img', 'operation_boot_page_url'], 'url'],
            [['operation_boot_page_ios_img', 'operation_boot_page_android_img', 'operation_boot_page_url', 'operation_boot_page_online_time', 'operation_boot_page_offline_time'], 'required'],
            [['operation_boot_page_residence_time'], 'integer', 'min' => 1],
//            [['operation_boot_page_online_time', 'operation_boot_page_offline_time'], 'date'],
        ];
    }

}
