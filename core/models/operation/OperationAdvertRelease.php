<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationAdvertRelease as CommonOperationAdvertRelease;
/**
 * This is the model class for table "{{%operation_advert_release}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_id
 * @property string $operation_advert_position_name
 * @property integer $operation_advert_content_id
 * @property integer $operation_advert_content_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertRelease extends CommonOperationAdvertRelease
{
    public function getAdvertList($city_id, $platform_id = 0, $version_id = 0, $position_id = 0){
        $data = OperationAdvertRelease::find()->asArray()->where(['city_id' => $city_id])->all();
        $d = array();
        $ids = array();
        foreach ((array)$data as $key => $value){
            $contents = unserialize($value['operation_release_contents']);
            array_push($d, $contents);
            array_push($ids, $contents['id'][0]);
        }
        $adverts = self::getAdvertListFrom($ids, $platform_id, $version_id, $position_id);
        return $adverts;
    }
    
    static private function getAdvertListFrom($ids, $platform_id, $version_id, $position_id){
        //foreach($contents)
        //$adverts = $this->getAdvertListFromPosition($position_id);
    }

    /**
     * 保存顺序之前，检查同一个位置上是否有时间冲突
     */
    public function saveReleaseAdvOrder($data)
    {
        $result = 0;

        foreach ($data as $id => $orders) {
            $model = OperationAdvertRelease::findOne($id);

            $city_name = $model->city_name;
            $starttime = $model->starttime;
            $endtime = $model->endtime;

            //如果没有设置时间，直接保存
            if (($starttime == '' || $starttime == '0000:00:00 00:00:00' || $starttime == null) && ($endtime == '' || $endtime == '0000:00:00 00:00:00' || $endtime == null)) {
                $model->id = $id;
                $model->advert_release_order = $orders;
                $model->save();
                $result += 1;

            //如果有设置时间，检测时间是否有重叠
            } else {
                $city_adv_data = OperationAdvertRelease::find()->asArray()->where(['city_id' => $model->city_id, 'advert_release_order' => $orders])->all();

                //没有同一个位置的广告直接保存
                if (empty($city_adv_data)) {
                    $model->id = $id;
                    $model->advert_release_order = $orders;
                    $model->save();
                    $result += 1;
                } else {
                    foreach ($city_adv_data as $key => $value) {
                        if (($orders == $value['advert_release_order']) &&
                            (($endtime < $value['starttime']) || ($starttime > $value['endtime']))) {
                            $model->id = $id;
                            $model->advert_release_order = $orders;
                            $model->save();
                            $result += 1;
                        } elseif ($orders != $value['advert_release_order']) {
                            $model->id = $id;
                            $model->advert_release_order = $orders;
                            $model->save();
                            $result += 1;
                        } else {
                        }
                    }
                }
            }
        }

        return $result;
    }
}
