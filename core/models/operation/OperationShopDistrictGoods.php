<?php

namespace core\models\operation;

use core\models\operation\OperationShopDistrict;
use core\models\operation\OperationCategory;
use core\models\operation\OperationCity;

use Yii;
use yii\data\ActiveDataProvider;

class OperationShopDistrictGoods extends \dbbase\models\operation\OperationShopDistrictGoods
{
    /**
     * 商圈服务项目状态
     */
    const SHOP_DISTRICT_GOODS_ONLINE = 1;
    const SHOP_DISTRICT_GOODS_OFFLINE = 2;

    /**
     * API状态码
     */
    const MISSING_PARAM = 0;
    const EMPTY_CONTENT = 1;

    public static $city_id;

    /**
     * 上线城市
     *
     * @param  array $post         要上线的城市，服务类型，服务项目和商圈信息
     * @param  sting $user_action  用户的操作:online上线操作，edit编辑操作
     * @return void
     */
    public static function saveOnlineCity($post, $user_action)
    {
        //城市数据
        $city_id = $post['city_id'];
        $city_name = $post['city_name'];

        unset($post['city_id']);
        unset($post['city_name']);
        unset($post['_csrf']);

        //去掉接收数据中没有选择的服务类型
        foreach ($post as $keys => $values) {
            if (!is_array($values)) {
                unset($post[$keys]);
            }
        }

        //服务类型的数据
        foreach ($post as $keys => $values) {

            //服务类型的id
            $operation_category_id = $keys;
            $operation_category_name = OperationCategory::getCategoryName($keys);

            //去掉接收数据中没有选中的或是没有输入销售价格的服务项目
            foreach ($values as $key => $value) {
                if (!isset($value['operation_goods_id']) || $value['operation_goods_id'] == ''
                    || !isset($value['operation_goods_price']) || $value['operation_goods_price'] == ''
                    || !isset($value['district']) || empty($value['district'])) {
                    unset($values[$key]);
                }
            }

            //如果没有输入销售价格,过滤掉
            if (!isset($values) || empty($values)) {
                continue;
            }

            //服务项目的数据
            foreach ($values as $key => $value) {
                $operation_goods_id = $value['operation_goods_id'];
                $operation_goods_name = $value['operation_goods_name'];
                $operation_goods_price = $value['operation_goods_price'];

                //删除掉旧数据，再插入新数据，只有在编辑的时候要删除，新增加的时候不能都删除
                //同一服务项目分次上线同城市不同商圈时不能删除之前的数据，要累加，但不能重复
                if ($user_action == 'edit') {
                    self::delCityShopDistrictGoods($operation_goods_id, $city_id);
                }

                $operation_goods_market_price = $value['operation_goods_market_price'] ?  $value['operation_goods_market_price']: 0;

                $operation_shop_district_goods_lowest_consume_num = $value['operation_shop_district_goods_lowest_consume_num'] ? $value['operation_shop_district_goods_lowest_consume_num'] : 0;

                $operation_spec_info = $value['operation_spec_info'];
                $operation_spec_strategy_unit = $value['operation_spec_strategy_unit'];

                //商圈的数据
                foreach ($value['district'] as $k => $v) {

                    //新上线项目时判断是否在同商圈上线过,上线过跳出本次循环
                    if ($user_action == 'online') {
                        $result = self::getCityShopDistrictGoodsId($operation_goods_id, $city_id, $v);
                        if ($result != false) {
                            continue;
                        }
                    }

                    $model = new OperationShopDistrictGoods();

                    //城市数据
                    $model->operation_city_id = $city_id;
                    $model->operation_city_name = $city_name;

                    //服务类型数据
                    $model->operation_category_id = $operation_category_id;
                    $model->operation_category_name = $operation_category_name;

                    //服务项目数据
                    $model->operation_goods_id = $operation_goods_id;
                    $model->operation_shop_district_goods_name = $operation_goods_name;
                    $model->operation_shop_district_goods_price = $operation_goods_price;
                    $model->operation_shop_district_goods_market_price = $operation_goods_market_price;
                    $model->operation_shop_district_goods_lowest_consume_num = $operation_shop_district_goods_lowest_consume_num;

                    //商圈数据
                    $model->operation_shop_district_id = $v;
                    $operation_shop_district_name = OperationShopDistrict::getShopDistrictName($v);
                    $model->operation_shop_district_name = $operation_shop_district_name;

                    //服务项目规格数据
                    $model->operation_spec_info = $operation_spec_info;
                    $model->operation_spec_strategy_unit = $operation_spec_strategy_unit;

                    if ($user_action == 'edit') {
                        $model->updated_at = time();
                    } elseif ($user_action == 'online') {
                        $model->created_at = time();
                    }

                    $model->insert();
                }
            }
        }
    }

    /**
     * 获取上线项目详情
     *
     * @param  integer  $city_id          城市编号
     * @param  integer  $shop_district    商圈编号
     * @param  integer  $goods_id         服务项目编号
     */
    public static function getShopDistrictGoodsInfo($city_id = '', $shop_district = '', $goods_id = ''){
        if (empty($city_id) || empty($shop_district) || empty($goods_id)) {
            return '';
        }else{
            $query = new \yii\db\Query();
            $query = $query->select([
                'osdg.operation_goods_id',
                'osdg.operation_category_id',
                'osdg.operation_category_name',
                'osdg.operation_shop_district_goods_name',
                'osdg.operation_shop_district_goods_price',
                'osdg.operation_shop_district_goods_lowest_consume',
                'osdg.operation_shop_district_goods_lowest_consume_num',
                'osdg.operation_spec_strategy_unit',
                'osdg.created_at',
                'og.operation_goods_introduction',
                'og.operation_goods_english_name',
                'og.operation_goods_price_description',
            ])
            ->from('{{%operation_shop_district_goods}} as osdg')
            ->leftJoin('{{%operation_goods}} as og','osdg.operation_goods_id= og.id')
            ->andFilterWhere([
                'osdg.operation_city_id' => $city_id,
                'osdg.operation_shop_district_id' => $shop_district,
                'osdg.operation_goods_id' => $goods_id,
                'osdg.operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $result = $dataProvider->query->one();
            return $result;
        }
    }

    public static function getShopDistrictGoodsList($city_id = '', $shop_district = ''){
        if(empty($city_id) || empty($shop_district)){
            return '';
        }else{
            return self::find()
                ->select([
                    'operation_goods_id',
                    'operation_shop_district_goods_name',
                    'operation_category_id',
                    'operation_category_name',
                    'operation_shop_district_goods_introduction',
                    'operation_shop_district_goods_price',
                    'operation_shop_district_goods_lowest_consume_num',
                    'operation_shop_district_goods_lowest_consume',
                    'operation_shop_district_goods_market_price',
                    'created_at'
                ])
                ->asArray()
                ->where([
                    'operation_city_id' => $city_id,
                    'operation_shop_district_id' => $shop_district,
                    'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE
                ])
                ->All();
        }
    }

    /**
     * 查询城市下面的所有商品
     *
     * @param  integer   $city_id  城市编号
     * @return string
     */
    public static function getCityShopDistrictGoodsList($city_id = ''){
        self::$city_id = $city_id;
        if(empty($city_id)){
            return '';
        }else{
            return self::find()->where(['operation_city_id' => $city_id])->groupBy('operation_goods_id');
        }
    }
    
    public static function getCityShopDistrictGoodsListArray($city_id = ''){
        return self::find()
            ->where([
                'operation_city_id' => $city_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ])
            ->groupBy('operation_goods_id')
            ->asArray()
            ->all();
    }

    /*
     * 城市下边商品的状态置为下架
     */
    public static function setCityShopDistrictGoodsStatus($goodsid, $cityid){
        Yii::$app->db->createCommand()->update(self::tableName(), ['operation_shop_district_goods_status' => 2], ['operation_goods_id' => $goodsid, 'operation_city_id' => $cityid])->execute();
    }

    /**
     * 删除城市下边商品
     *
     * @param integer $goods_id    服务项目编号
     * @param integer $city_id     上线城市编号
     */
    public static function delCityShopDistrictGoods($goods_id, $city_id)
    {
        return self::deleteAll(['operation_goods_id' => $goods_id, 'operation_city_id' => $city_id]);
    }

    /**
     * 查找商品是否存在
     */
    public static function getCityShopDistrictGoodsInfo($city_id, $goods_id){
        return self::find()->asArray()->where(['operation_city_id' => $city_id, 'operation_goods_id' => $goods_id])->All();
    }
    
    
    /**
     * 根据城市获取已上线的服务品类数据
     *
     * @param  integer $city_id     城市编号,暂无
     * @param  string  $city_name   城市名称
     * @return array
     */
    public static function getCityCategory($city_name = '', $city_id = '')
    {
        if ($city_id == '' && $city_name == '') {
            return ['code' => self::MISSING_PARAM, 'errmsg' => '参数错误'];
        }

        if (isset($city_name) && $city_name != '') {
            $query = new \yii\db\Query();
            $query = $query->select([
                'oc.operation_category_name',
                'osdg.operation_shop_district_goods_name',
                'oc.id',
                'oc.operation_category_icon',
                'oc.operation_category_url',
                'oc.operation_category_price_description',
                'oc.operation_category_introduction',
            ])
            ->from('{{%operation_shop_district_goods}} as osdg')
            ->leftJoin('{{%operation_category}} as oc','osdg.operation_category_id = oc.id')
            ->groupBy('osdg.operation_category_name')
            ->andFilterWhere([
                //'operation_city_id' => $city_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ]);

            $query->andFilterWhere(['like', 'osdg.operation_city_name', $city_name]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $result = $dataProvider->query->all();
            if (isset($result) && count($result) > 0) {
                return $result;
            } else {
                return ['code' => self::EMPTY_CONTENT, 'errmsg' => '参数错误'];
            }
        }

    }
    
    public static function getCityGoodsOpenShopDistrictNum($city_id, $goods_id)
    {
        $data = self::find()
            ->asArray()
            ->where([
                'operation_city_id' => $city_id,
                'operation_goods_id' => $goods_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE
            ])
            ->all();
        return count($data);
    }

    /**
     * 判断城市下是否有上线的服务项目
     */
    public static function getCityGoodsOnlineNum($city_id)
    {
        $data = self::find()
            ->asArray()
            ->where([
                'operation_city_id' => $city_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE
            ])
            ->all();
        return count($data);
    }

    public static function getGoodsByCity($city_name)
    {
        $query = new \yii\db\Query();
        $query = $query->select([
            'sdgoods.operation_city_id',
            'sdgoods.operation_city_name',
            'sdgoods.operation_category_id',
            'sdgoods.operation_category_name',

            'goods.id as goods_id',
            'goods.operation_goods_no',
            'goods.operation_goods_name',
            'goods.operation_goods_introduction',
            'goods.operation_goods_english_name',
            'goods.operation_goods_img',
            'goods.operation_goods_app_ico',
            'goods.operation_goods_pc_ico',
            'goods.operation_goods_price',
            'goods.operation_spec_strategy_unit',
            'goods.operation_goods_price_description',
        ])->distinct()
            ->from('{{%operation_shop_district_goods}} as sdgoods')
            ->leftJoin('{{%operation_goods}} as goods','sdgoods.operation_goods_id = goods.id')
            ->andFilterWhere([
                'operation_city_name' => $city_name,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider->query->all();
    }

    public static function getGoodsByCityCategory($city_name,$category_id)
    {
        $query = new \yii\db\Query();
        $query = $query->select([
            'sdgoods.operation_city_id',
            'sdgoods.operation_city_name',
            'sdgoods.operation_category_id',
            'sdgoods.operation_category_name',

            'goods.id as goods_id',
            'goods.operation_goods_no',
            'goods.operation_goods_name',
            'goods.operation_goods_introduction',
            'goods.operation_goods_english_name',
            'goods.operation_goods_img',
            'goods.operation_goods_app_ico',
            'goods.operation_goods_pc_ico',
            'goods.operation_goods_price',
            'goods.operation_spec_strategy_unit',
            'goods.operation_goods_price_description',
        ])->distinct()
            ->from('{{%operation_shop_district_goods}} as sdgoods')
            ->leftJoin('{{%operation_goods}} as goods','sdgoods.operation_goods_id = goods.id')
            ->andFilterWhere([
                'operation_city_name' => $city_name,
                'sdgoods.operation_category_id' => $category_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider->query->all();
    }

    public static function getGoodsCategoryInfo($city_id,$shop_district_id,$category_name)
    {
        return self::find()->where([
            'operation_city_id' => $city_id,
            'operation_category_name' => $category_name,
            'operation_shop_district_id' => $shop_district_id,
            'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
        ]);
    }

    /**
     * 根据服务项目id和城市id获取商品在商圈的具体信息
     *
     * @param  integer  $operation_goods_id    商品在商圈里的编号
     * @param  integer  $city_id               城市编号
     * @return array    $result                上线商品的信息
     */
    public static function getDistrictGoodsInfo($operation_goods_id, $city_id)
    {
        $result = self::find()
            ->select([
                'operation_goods_id',
                'operation_shop_district_id',
                'operation_category_id',
                'operation_spec_strategy_unit',
                'operation_shop_district_goods_price',
                'operation_shop_district_goods_market_price',
                'operation_shop_district_goods_lowest_consume_num',
            ])
            ->where([
                'operation_goods_id' => $operation_goods_id,
                'operation_city_id' => $city_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ])
            ->asArray()
            ->all();

        return $result;
    }

    /**
     * 查找商圈是否存在;有,则代表上线
     *
     * @param integer $district_id    商圈id
     */
    public static function getShopDistrict($district_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where([
                'operation_shop_district_id' => $district_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ])
            ->asarray()
            ->one();

        if (isset($data['id']) && $data['id'] > 0) {
            return $data['id'];
        } else {
            return 0;
        }

    }

    /**
     * 删除商圈关联删除商圈下边服务项目
     *
     * @param integer $operation_shop_district_id    商圈id
     */
    public static function delShopDistrictGoods($operation_shop_district_id)
    {
        self::deleteAll(['operation_shop_district_id' => $operation_shop_district_id]);
    }

    /**
     * 删除服务项目关联删除服务项目对应的商圈
     *
     * @param integer $goods_id    商圈id
     */
    public static function delShopDistrict($operation_goods_id)
    {
        self::deleteAll([
            'operation_goods_id' => $operation_goods_id,
            'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_OFFLINE,
        ]);
    }

    /**
     * 判断服务项目是否在商圈上线
     *
     * @param  integer $goods_id 服务项目编号
     * @return bool    $result   判断结果
     */
    public static function getShopDistrictGoods($operation_goods_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where([
                'operation_goods_id' => $operation_goods_id,
                'operation_shop_district_goods_status' => self::SHOP_DISTRICT_GOODS_ONLINE,
            ])
            ->asarray()
            ->one();

        if (isset($data['id']) && $data['id'] > 0) {
            return $data['id'];
        } else {
            return false;
        }
    }

    /**
     * 更新冗余的服务项目名称
     * ps:后来发现冗余的不仅仅是名称，还有所属的品类信息
     *
     * @param  integer   $operation_goods_id                  服务项目编号
     * @param  string    $operation_shop_district_goods_name  服务项目名称
     * @param  integer   $operation_category_id               服务品类编号
     * @param  string    $operation_category_name             服务品类名称
     * @return void
     */
    public static function updateGoodsInfo($operation_goods_id, $operation_shop_district_goods_name, $operation_category_id, $operation_category_name)
    {
        self::updateAll([
            'operation_shop_district_goods_name' => $operation_shop_district_goods_name,
            'operation_category_id'              => $operation_category_id,
            'operation_category_name'            => $operation_category_name,
        ],
        'operation_goods_id= ' . $operation_goods_id);
    }

    /**
     * 更新冗余的服务品类名称
     *
     * @param integer   $operation_category_id      服务品类编号
     * @param string    $operation_category_name    服务品类名称
     */
    public static function updateCategoryName($operation_category_id, $operation_category_name)
    {
        self::updateAll(['operation_category_name' => $operation_category_name], 'operation_category_id= ' . $operation_category_id);
    }

    /**
     * 在城市下线下点击下线时修改服务项目状态
     *
     * @param integer   $operation_goods_id                   服务项目编号
     * @param integer   $operation_city_id                    点击的城市编号
     * @param integer   $operation_shop_district_goods_status 要修改的状态
     */
    public static function updateShopDistrictGoodsStatus($operation_goods_id, $operation_city_id, $operation_shop_district_goods_status)
    {
        self::updateAll(
            ['operation_shop_district_goods_status' => $operation_shop_district_goods_status],
            ['operation_goods_id' => $operation_goods_id, 'operation_city_id' => $operation_city_id]
        );
    }

    /**
     * 更新冗余的规格名称
     *
     * @param integer   $operation_spec_info            规格编号
     * @param string    $operation_spec_strategy_unit   规格单位备注
     */
    public static function updateGoodsSpec($operation_spec_info, $operation_spec_strategy_unit)
    {
        self::updateAll(['operation_spec_strategy_unit' => $operation_spec_strategy_unit], 'operation_spec_info = ' . $operation_spec_info);
    }

    /**
     * 上线新服务项目时，判断在同一个商圈是否上线过
     *
     * @param  integer $goods_id    服务项目编号
     * @param  integer $city_id     上线城市编号
     * @param  integer $district_id 上线商圈编号
     * @return integer id if exist,bool false if not exist
     */
    public static function getCityShopDistrictGoodsId($goods_id, $city_id, $district_id)
    {
        $data = self::find()
            ->select(['id'])
            ->where([
                'operation_city_id' => $city_id,
                'operation_goods_id' => $goods_id,
                'operation_shop_district_id' => $district_id,
            ])
            ->asArray()
            ->one();

        if (isset($data['id']) && $data['id'] > 0) {
            return $data['id'];
        } else {
            return false;
        }
    }
}
