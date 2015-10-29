<?php
namespace dbbase\components;

use yii\helpers\ArrayHelper;
class BankHelper
{
    /**
     * 获取所有银行
     */
    public static function getBanks()
    {
        $str = file_get_contents('./../../common/components/banks.txt');
        $data = explode(PHP_EOL, $str);
        foreach ($data as $key=>$row){
            $data[$key] = explode("\t", $row);
        }
        return $data;
    }
    /**
     * 获取银行名称列表
     */
    public static function getBankNames()
    {
        $data = self::getBanks();
        return ArrayHelper::map($data, 2, 2);
    }
}