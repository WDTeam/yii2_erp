<?php

namespace dbbase\models\order;

use Yii;

/**
 * This is the model class for table "ejj_order_dispatcher_kpi".
 *
 * @property string $id
 * @property string $system_user_id
 * @property string $system_user_name
 * @property integer $dispatcher_kpi_date
 * @property integer $dispatcher_kpi_free_time
 * @property integer $dispatcher_kpi_busy_time
 * @property integer $dispatcher_kpi_rest_time
 * @property integer $dispatcher_kpi_end_at
 * @property integer $dispatcher_kpi_obtain_count
 * @property integer $dispatcher_kpi_assigned_count
 * @property double $dispatcher_kpi_assigned_rate
 * @property integer $dispatcher_kpi_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OrderDispatcherKpi extends \yii\db\ActiveRecord
{
    public $dispatcher_kpi_free_time_sum;//7日空闲时间
    public $dispatcher_kpi_busy_time_sum;//7日忙碌时间
    public $dispatcher_kpi_rest_time_sum;//7日小休时间
    public $dispatcher_kpi_obtain_count_sum;//7日获得派单数
    public $dispatcher_kpi_assigned_count_sum;//7日指派成功数
    public $dispatcher_kpi_free_time_avg;//7日平均空闲时间
    public $dispatcher_kpi_busy_time_avg;//7日平均忙碌时间
    public $dispatcher_kpi_rest_time_avg;//7日平均小休时间
    public $dispatcher_kpi_obtain_count_avg;//7日平均获得派单数
    public $dispatcher_kpi_assigned_count_avg;//7日平均指派成功数
    public $dispatcher_kpi_assigned_rate_avg;//7日平均指派平均率
    public $kpi_count;//有效考核天数
    public $non_assign_order_count;//系统待派单数
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_order_dispatcher_kpi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','system_user_id', 'dispatcher_kpi_date', 'dispatcher_kpi_free_time', 'dispatcher_kpi_busy_time', 'dispatcher_kpi_rest_time', 'dispatcher_kpi_end_at', 'dispatcher_kpi_obtain_count', 'dispatcher_kpi_assigned_count', 'dispatcher_kpi_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['dispatcher_kpi_free_time_avg','dispatcher_kpi_busy_time_avg', 'dispatcher_kpi_rest_time_avg', 'dispatcher_kpi_obtain_count_avg', 'dispatcher_kpi_assigned_count_avg', 'dispatcher_kpi_assigned_rate_avg','non_assign_order_count'], 'integer'],
            [['dispatcher_kpi_assigned_rate'], 'number'],
            [['system_user_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'system_user_id' => 'System User ID',
            'system_user_name' => 'System User Name',
            'dispatcher_kpi_date' => 'Dispatcher Kpi Date',
            'dispatcher_kpi_free_time' => 'Dispatcher Kpi Free Time',
            'dispatcher_kpi_busy_time' => 'Dispatcher Kpi Busy Time',
            'dispatcher_kpi_rest_time' => 'Dispatcher Kpi Rest Time',
            'dispatcher_kpi_end_at' => 'Dispatcher Kpi End At',
            'dispatcher_kpi_obtain_count' => 'Dispatcher Kpi Obtain Count',
            'dispatcher_kpi_assigned_count' => 'Dispatcher Kpi Assigned Count',
            'dispatcher_kpi_assigned_rate' => 'Dispatcher Kpi Assigned Rate',
            'dispatcher_kpi_status' => 'Dispatcher Kpi Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }

    /**
     * @introduction 查询KPI的7日平均值
     * @author zhangrenzhao
     * @date 2015-11-04
     * @param $system_user_id
     * @param $dispatcher_kpi_date(格式9999-99-99）
     */
    public static function queryHistoricalAgvKpi($system_user_id,$dispatcher_kpi_date){

        //时间从
        $dispatcher_kpi_date_from=$dispatcher_kpi_date-7*60*60*24;

        //时间自
        $dispatcher_kpi_date_to=$dispatcher_kpi_date-1*60*60*24;;


        //执行语句
        $sql=" SELECT SUM(dispatcher_kpi_free_time) dispatcher_kpi_free_time_sum,"."
               SUM(dispatcher_kpi_busy_time) dispatcher_kpi_busy_time_sum,"."
               SUM(dispatcher_kpi_rest_time) dispatcher_kpi_rest_time_sum,"."
               SUM(dispatcher_kpi_obtain_count) dispatcher_kpi_obtain_count_sum,"."
               SUM(dispatcher_kpi_assigned_count) dispatcher_kpi_assigned_count_sum, "."
               COUNT(system_user_id) kpi_count "."
               FROM ejj_order_dispatcher_kpi WHERE is_del=0 AND system_user_id=".$system_user_id."
               AND dispatcher_kpi_date >=".$dispatcher_kpi_date_from." AND dispatcher_kpi_date<=".$dispatcher_kpi_date_to;
        $kpi_sum_obj = self::findBySql($sql)->one();
        //计算7日平均天数
        $avg_kpi=[];
        if($kpi_sum_obj->kpi_count>=7){//大于等于7
            $avg_kpi=[
                'dispatcher_kpi_free_time_avg'=>$kpi_sum_obj->dispatcher_kpi_free_time_sum/7,
                'dispatcher_kpi_busy_time_avg'=>$kpi_sum_obj->dispatcher_kpi_busy_time_sum/7,
                'dispatcher_kpi_rest_time_avg'=>$kpi_sum_obj->dispatcher_kpi_rest_time_sum/7,
                'dispatcher_kpi_obtain_count_avg'=>$kpi_sum_obj->dispatcher_kpi_obtain_count_sum/7,
                'dispatcher_kpi_assigned_count_avg'=>$kpi_sum_obj->dispatcher_kpi_assigned_count_sum/7,
                'dispatcher_kpi_assigned_rate_avg'=>$kpi_sum_obj->dispatcher_kpi_assigned_count_sum/$kpi_sum_obj->dispatcher_kpi_obtain_count_sum,
            ];
        }else if($kpi_sum_obj->kpi_count>0){//大于0，小于7
            $avg_kpi=[
                'dispatcher_kpi_free_time_avg'=>$kpi_sum_obj->dispatcher_kpi_free_time_sum/$kpi_sum_obj->kpi_count,
                'dispatcher_kpi_busy_time_avg'=>$kpi_sum_obj->dispatcher_kpi_busy_time_sum/$kpi_sum_obj->kpi_count,
                'dispatcher_kpi_rest_time_avg'=>$kpi_sum_obj->dispatcher_kpi_rest_time_sum/$kpi_sum_obj->kpi_count,
                'dispatcher_kpi_obtain_count_avg'=>$kpi_sum_obj->dispatcher_kpi_obtain_count_sum/$kpi_sum_obj->kpi_count,
                'dispatcher_kpi_assigned_count_avg'=>$kpi_sum_obj->dispatcher_kpi_assigned_count_sum/$kpi_sum_obj->kpi_count,
                'dispatcher_kpi_assigned_rate_avg'=>$kpi_sum_obj->dispatcher_kpi_assigned_count_sum/$kpi_sum_obj->dispatcher_kpi_obtain_count_sum,
            ];
        }else{//等于0
            $avg_kpi=[
                'dispatcher_kpi_free_time_avg'=>0,
                'dispatcher_kpi_busy_time_avg'=>0,
                'dispatcher_kpi_rest_time_avg'=>0,
                'dispatcher_kpi_obtain_count_avg'=>0,
                'dispatcher_kpi_assigned_count_avg'=>0,
                'dispatcher_kpi_assigned_rate_avg'=>0
            ];
        }
        //返回参数
        return $avg_kpi;
    }
    /**
     * @introduction 查询当日KPI
     * @author zhangrenzhao
     * @date 2015-11-04
     * @param $system_user_id
     * @param $dispatcher_kpi_date
     */
    public static function queryTodateKpi($system_user_id,$dispatcher_kpi_date){
        return self::findOne(['system_user_id'=>$system_user_id,'dispatcher_kpi_date'=>$dispatcher_kpi_date]);
    }

}
