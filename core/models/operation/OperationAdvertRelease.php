<?php

namespace core\models\operation;

use core\models\operation\OperationPlatformVersion;
use core\models\operation\OperationAdvertPosition;

use Yii;
use yii\data\ActiveDataProvider;

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
class OperationAdvertRelease extends \dbbase\models\operation\OperationAdvertRelease
{

    /**
     * 广告状态
     */
    const ADVERT_ONLINE = 1;
    const ADVERT_OFFLINE = 2;

    /**
     * API状态码
     */
    const MISSING_PARAM = 0;
    const EMPTY_CONTENT = 1;

    public function getAdvertList($city_id, $platform_id = 0, $version_id = 0, $position_id = 0)
    {
        $data = OperationAdvertRelease::find()->asArray()->where(['city_id' => $city_id])->all();
        $d = array();
        $ids = array();
        foreach ((array)$data as $key => $value) {
            $contents = unserialize($value['operation_release_contents']);
            array_push($d, $contents);
            array_push($ids, $contents['id'][0]);
        }
        $adverts = self::getAdvertListFrom($ids, $platform_id, $version_id, $position_id);
        return $adverts;
    }

    /**
     * 根据城市名称，平台名称和平台版本，获取对应位置的广告内容
     *
     * @param    string  $city_name               城市名称
     * @param    string  $platform_name           平台名称
     * @param    string  $platform_version_name   平台版本
     * @param    string  $position_name           位置
     * @return   mix     如果没有数据则为string,有数据则为数据
     */
    public static function getCityAdvertInfo($city_name, $platform_name, $platform_version_name, $position_name = 'banner')
    {
        if (!isset($city_name) || $city_name == '' || !isset($platform_name) || $platform_name == '' || !isset($platform_version_name) || $platform_version_name=="") {
            return ['code' => self::MISSING_PARAM, 'errmsg' => '参数不正确'];
        }
        //北京市--ios--4.4--banner
        $platform_id = OperationPlatformVersion::getPlatformId($platform_name, $platform_version_name);
        if ($platform_id == false) {
            return ['code' => self::EMPTY_CONTENT, 'errmsg' => '没有对应数据'];
        }

        $position_id = OperationAdvertPosition::getAdvertPositionId($position_name);
        if ($position_id == false) {
            return ['code' => self::EMPTY_CONTENT, 'errmsg' => '没有对应数据'];
        }
        
        $query = new \yii\db\Query();
        $query = $query->select([
            'oar.advert_release_order',
            'oar.starttime',
            'oar.endtime',
            'oac.operation_advert_content_name',
            'oac.operation_advert_picture_text',
            'oac.operation_advert_url',
        ])
        ->from('{{%operation_advert_release}} as oar')
        ->leftJoin('{{%operation_advert_content}} as oac','oar.advert_content_id = oac.id')
        ->andFilterWhere([
            'oar.status' => self::ADVERT_ONLINE,
            'oac.platform_id' => $platform_id,
            'oac.position_id' => $position_id,
        ]);

        $query->andFilterWhere(['like', 'oar.city_name', $city_name]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $result = $dataProvider->query->all();
        if (isset($result) && count($result) > 0) {
            return $result;
        } else {
            return ['code' => self::EMPTY_CONTENT, 'errmsg' => '没有对应数据'];
        }
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
