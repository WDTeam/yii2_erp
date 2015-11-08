<?php

namespace core\models\order;

use Yii;
use core\models\system\SystemUserSearch;

/**
 * This is the model class for table "ejj_order_dispatcher_kpi".
 *
 * @property string  $id
 * @property string  $system_user_id
 * @property string  $system_user_name
 * @property integer $dispatcher_kpi_date
 * @property integer $dispatcher_kpi_free_time
 * @property integer $dispatcher_kpi_busy_time
 * @property integer $dispatcher_kpi_rest_time
 * @property integer $dispatcher_kpi_end_at
 * @property integer $dispatcher_kpi_obtain_count
 * @property integer $dispatcher_kpi_assigned_count
 * @property double  $dispatcher_kpi_assigned_rate
 * @property integer $dispatcher_kpi_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OrderDispatcherKpi extends \dbbase\models\order\OrderDispatcherKpi{

    /**
     * @introduction a1.基于用户ID和考核日期计算7日内平均PKI；
     *               a2.查询待系统指派订单；
     *               a3.基于用户ID和考核日期查询当日PKI(UI需根据数据状态，显示不同的页面信息)
     * @author zhangrenzhao
     * @date 2015-11-04
    　* @param $attributes[
     *        system_user_id 客服ID
     *        dispatcher_kpi_date 考核日期]
     * @out  outAttributes[
     *          $id,表order_dispatcher_kpi主键
     *          system_user_id，客服ID（必须有）
     *          dispatcher_kpi_date 考核日期（必须有）
     *          $dispatcher_kpi_status   数据状态
     *          $dispatcher_kpi_free_time 空闲时间
     *          $dispatcher_kpi_busy_time 忙碌时间
     *          $dispatcher_kpi_rest_time 小休时间
     *          $dispatcher_kpi_end_at    收工时间
     *          $dispatcher_kpi_obtain_count 获得派单数
     *          $dispatcher_kpi_assigned_count 已派单数
     *          $dispatcher_kpi_assigned_rate  指派成功率
     *          ----------------------------------------
     *          $non_assign_order_count  待系统指派订单（需订单模块提供接口）
     *          -----------------------------------------
     *          $dispatcher_kpi_free_time_avg 7日平均空闲时间
     *          $dispatcher_kpi_busy_time_avg 7日平均忙碌时间
     *          $dispatcher_kpi_rest_time_avg 7日平均小休时间
     *          $dispatcher_kpi_obtain_count_avg 7日平均获得派单数
     *          $dispatcher_kpi_assigned_count_avg 7日平均已派单数
     *          $dispatcher_kpi_assigned_rate_avg  7日平均指派成功率
     *       ]
     * @from  1)【用户进入派单页面】
     */
    public function queryHistoricalKpi($system_user_id,$dispatcher_kpi_date){
        //1.获取当日记录
        $model=OrderDispatcherKpi::queryTodateKpi($system_user_id,$dispatcher_kpi_date);

        //2.设置页面参数
        if($model==null){
            $model=new OrderDispatcherKpi();
            $model->setAttributes([
                'system_user_id'=>$system_user_id,
                'dispatcher_kpi_date'=>$dispatcher_kpi_date,
            ]);
        }

        //3.获得7日内平均PKI，并放入this对象
        $agvKpi=OrderDispatcherKpi::queryHistoricalAgvKpi($system_user_id,$dispatcher_kpi_date);
        if($agvKpi!=null){
            $model->setAttributes($agvKpi);
        }

        //4.查询当前系统待派单总数（需订单模块哥们提供）
        $non_assign_order_count=100;//getNonAssignOrderCount()调用其他模块方法
        $model->setAttributes([
            'non_assign_order_count' =>$non_assign_order_count,
        ]);

        //5.回写参数
        return $model;
    }
    /**
     * @introduction 更新客服当日KPI，并返回最新OrderDispatcherKpi对象
     * @author zhangrenzhao
     * @date   2015-11-04
     * @param  $attributes[
     *          $id,表order_dispatcher_kpi主键（必须有，NULL或order_dispatcher_kpi主键，进入派单界面为NULL，
     *              系统会基于客服ID+日期，判断是否已有数据）
     *          $system_user_id，客服ID（必须有）
     *          $dispatcher_kpi_date 考核日期（必须有）
     *          $dispatcher_kpi_status   数据状态（必须有，0空闲、1忙碌、2小休、3收工）
     *          $dispatcher_kpi_free_time 空闲时间（0或日总空闲时间，UI自增，直接入库）
     *          $dispatcher_kpi_busy_time 忙碌时间（0或日总忙碌时间，UI自增，直接入库）
     *          $dispatcher_kpi_rest_time 小休时间（0或日总小休时间，UI自增，直接入库）
     *          $dispatcher_kpi_end_at    收工时间
     *          $dispatcher_kpi_obtain_count 获得派单数(0或日总获得派单数，UI自增，直接入库)
     *          $dispatcher_kpi_assigned_count 已派单数（0或日总成功派单数，UI自增，直接入库）
     *          $dispatcher_kpi_assigned_rate 成功判断率
     *          （0或$dispatcher_kpi_assigned_count/$dispatcher_kpi  _assigned_count,UI计算，直接入库）
     *           ]
     * @out   NULL或OrderDispatcherKpi对象
     * @from　1）【单击“开工啦”】状态：空闲；客服ID；考核日期；
     *        2）【系统派单成功】状态：忙碌；获得派单数+1；空闲时间：UI自增数;
     *        3）【客服指派成功】指派成功数+1；
     *        4）【成功指派，单击“我要接活”或系统10秒后】状态：空闲；忙碌时间：UI自增数；
     *        5）【成功指派，单击“小休”】状态：小休；忙碌时间：UI自增数
     *        6）【成功指派，单击“收工啦”】状态：收工；忙碌时间：UI自增数；收工时间；
     *        7）【小休后，单击“我要激活”】状态：空闲；小休时间：UI自增数
     *　　　 　8）【小休后，单击“收工啦”】状态：状态：收工；小休时间：UI自增数；收工时间
     *        9）【误关页面】UI自动触发updateDisatcherKpi，保存当前状态。UI缓存自动进入小休状态，计算小休时间。
     *                      重新打开页面，UI根据缓存数据自动显示对应页面。
     *        10）【误关浏览器】系统停止派单。UI自动触发updateDisatcherKpi，保存当前数据，进入小休状态，并更新收工时间。
     *                       重启浏览器，当前时间与收工时间差额，并入小休时间。UI显示最新数据，不显示“开工啦”按钮。
     *        11）【当机】系统停止派单。系统不做处理，重启系统，UI显示最近一次保存数据，不显示“开工啦”按钮。
     */
    public function updateDisatcherKpi($attributes){
        //0.定义参数，判断是否已有记录
        $model=new OrderDispatcherKpi;
        $id= $attributes['id'];
        if($id!=null and $id!=""){
            $model=OrderDispatcherKpi::findOne(['id'=>$id]);
        }

        //1.读取attributes，写入model
        $model->setAttributes($attributes);

        //2.创建记录时，初始化
        if($model->id==null){
            $model->setAttributes([
                'is_del' => 0,
                'created_at'=>time(),
            ]);
        }

        //3.设置更新时间
        $model->setAttributes([
            'updated_at'=>time(),
        ]);

        //4.读取客服信息
        $systemUser=(new SystemUserSearch())->findOne(['id'=>$model->system_user_id]);
        if($systemUser!=null){
            $model->setAttributes([
                'system_user_name' => $systemUser->username,
            ]);
        }

        //5.创建或更新记录
        if($model->save()){
            //5.1.更新成功，回写最新OrderDispatcherKpi对象//axjax无需返回
            $ml=OrderDispatcherKpi::queryTodateKpi($model->system_user_id,$model->dispatcher_kpi_date);
            return $ml->id;
        }else{
            return false;
        }
    }
}