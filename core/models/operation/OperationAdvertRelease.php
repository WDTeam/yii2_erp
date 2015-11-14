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
     * @param   string  $city_name               城市名称
     * @param   string  $platform_name           平台名称
     * @param   string  $platform_version_name   平台版本
     * @param   string  $position_name           位置
     * @return  array   $result                  结果
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

        foreach ($data as $id => $orders) {

            //没有输入顺序的广告直接过滤掉
            //if (!isset($orders) || empty($orders)) {
                //continue;
            //}

            $model = OperationAdvertRelease::findOne($id);
            $adv_data = self::getReleaseAdvertInfo($id);

            //当前要插入顺序的广告信息
            $city_id             = $adv_data['city_id'];
            $starttime           = $adv_data['starttime'];
            $endtime             = $adv_data['endtime'];
            $position_id         = $adv_data['position_id'];
            $platform_id         = $adv_data['platform_id'];
            $platform_version_id = $adv_data['platform_version_id'];

            //如果没有设置时间，直接保存
            if (($starttime == '' || $starttime == '0000:00:00 00:00:00' || $starttime == null) && ($endtime == '' || $endtime == '0000:00:00 00:00:00' || $endtime == null)) {
                $model->id = $id;
                $model->advert_release_order = $orders;
                $model->save();

            //如果有设置时间,检测同城市,同位置,同平台,同版本,同排序点的广告时间是否有重叠
            } else {

                $repeat_city_adv_data = self::getReleaseAdvertInfo('', $city_id, $position_id, $platform_id, $platform_version_id, $orders, $id);

                //没有同一个位置的广告直接保存
                if (empty($repeat_city_adv_data)) {
                    $model->id = $id;
                    $model->advert_release_order = $orders;
                    $model->save();
                } else {
                    $mark = 0;

                    //没有任何一个广告有重叠，则保存
                    foreach ($repeat_city_adv_data as $key => $value) {
                        if (($endtime < $value['starttime']) || ($starttime > $value['endtime'])) {
                            $mark += 0;
                        } else {
                            $mark += 1;
                        }
                    }

                    if ($mark == 0) {
                        $model->id = $id;
                        $model->advert_release_order = $orders;
                        $model->save();
                    }
                }
            }
        }

        //return 'info';
    }

    /**
     * 联动删除已发布广告信息
     *
     * @param integer   $advert_content_id     广告内容编号
     */
    public static function updateAdvertReleaseStatus($advert_content_id)
    {
        self::deleteAll([
            'advert_content_id' => $advert_content_id,
        ]);
    }

    /**
     * 1,根据已发布广告编号获取广告详情
     * 2,检测同城市,同位置,同平台,同版本,同排序点的广告时间是否有重叠
     *
     * @param  integer  $id                   已发布广告编号
     * @param  integer  $city_id              已发布广告城市编号
     * @param  integer  $position_id          已发布广告位置编号
     * @param  integer  $platform_id          已发布广告平台编号
     * @param  integer  $platform_version_id  已发布广告平台版本编号
     * @param  integer  $orders               已发布广告位置编号
     * @param  integer  $except_id            已发布广告编号,检测时不查找自己
     * @return array    $result  结果
     */
    public static function getReleaseAdvertInfo($id = '', $city_id = '', $position_id = '', $platform_id = '', $platform_version_id = '', $orders = '', $except_id = '')
    {
        //if (!isset($id) || empty($id) || !is_numeric($id)) {
            //return ['code' => self::MISSING_PARAM, 'errmsg' => '参数不正确'];
        //}

        $query = new \yii\db\Query();
        $query = $query->select([
            'oar.id',
            'oar.city_id',
            'oar.starttime',
            'oar.endtime',
            'oar.advert_release_order',
            'oac.position_id',
            'oac.platform_id',
            'oac.platform_version_id',
        ])
        ->from('{{%operation_advert_release}} as oar')
        ->leftJoin('{{%operation_advert_content}} as oac','oar.advert_content_id = oac.id');

        if (($id != '') && is_numeric($id)) {
            $query->andFilterWhere(['oar.id' => $id]);
        }
        if (($city_id != '') && is_numeric($city_id)) {
            $query->andFilterWhere(['oar.city_id' => $city_id]);
        }
        if (($position_id != '') && is_numeric($position_id)) {
            $query->andFilterWhere(['oac.position_id' => $position_id]);
        }
        if (($platform_id != '') && is_numeric($platform_id)) {
            $query->andFilterWhere(['oac.platform_id' => $platform_id]);
        }
        if (($platform_version_id != '') && is_numeric($platform_version_id)) {
            $query->andFilterWhere(['oac.platform_version_id' => $platform_version_id]);
        }
        if (($orders != '') && is_numeric($orders)) {
            $query->andFilterWhere(['oar.advert_release_order' => $orders]);
        }
        if (($except_id != '') && is_numeric($except_id)) {
            $query->andFilterWhere(['!=', 'oar.id', $except_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (($id != '') && is_numeric($id)) {
            $result = $dataProvider->query->one();
        } else {
            $result = $dataProvider->query->all();
        }

        return $result;
    }
}
