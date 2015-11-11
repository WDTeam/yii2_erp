<?php

namespace boss\models\operation;

use boss\models\operation\OperationAdvertRelease;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OperationAdvertReleaseSearch represents the model behind the search form about `boss\models\operation\OperationAdvertRelease`.
 */
class OperationAdvertReleaseSearch extends OperationAdvertRelease
{
    //平台名称
    public $platform_name;

    //广告名称
    public $operation_advert_content_name;

    //平台版本
    public $platform_version_name;

    //广告位置
    public $position_name;

    public function rules()
    {
        return [
            [['id', 'advert_content_id', 'city_id', 'status', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['starttime', 'endtime'], 'date'],
            [['city_name', 'starttime', 'endtime'], 'safe'],
            [['platform_name', 'operation_advert_content_name', 'platform_version_name', 'position_name'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationAdvertRelease::find()
            ->joinWith(['operationAdvertContent']);

        //$query->orderBy([OperationAdvertRelease::tableName().".id"=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        if (empty($this->city_name)) {
            $session = Yii::$app->session;
            if (isset($this->city_id) && $this->city_id != 0) {
                $session['city_id'] = $this->city_id;
            }
        } else {
            $session['city_id'] = '';
        }

        $query->andFilterWhere([
            'city_id' => $session['city_id'],
            'operationAdvertContent.platform_name' => $this->platform_name,
            'operationAdvertContent.platform_version_name' => $this->platform_version_name,
        ]);

        $query->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'operationAdvertContent.position_name', $this->position_name])
            ->andFilterWhere(['like', 'operationAdvertContent.operation_advert_content_name', $this->operation_advert_content_name]);

        return $dataProvider;
    }
}
