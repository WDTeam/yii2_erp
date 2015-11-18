<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\ManualOrderSerach $searchModel
 */

$this->title = Yii::t('order', 'ManualOrder');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <div class="panel panel-info">
        <div id="order_assign">
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="500">
                            <h5 class="col-sm-12" id="order_code">订单编号：<?=$model['order']->order_code;?><input type="hidden" id="order_id" value="<?=$model['order']->id;?>"></h5>
                            <h5 class="col-sm-12" id="booked_time_range"><?=$model['booked_time_range'];?></h5>
                            <h5 class="col-sm-12" id="order_address"><?=$model['order']->order_address;?></h5>
                        </td>
                        <td>
                            <?php if($model['ext_pay']->pay_channel_id == 2):?>
                                <h5 id="must_pay_info" class="col-sm-12">需收取<?=$model['order']->order_money;?>元</h5>
                                <h5 id="pay_info" class="col-sm-12">总金额<?=$model['order']->order_money;?>元</h5>
                            <?php elseif($model['ext_pay']->pay_channel_type_id==1 || $model['ext_pay']->pay_channel_type_id==2):?>
                                <h5 id="must_pay_info" class="col-sm-12">需收取<?=$model['order']->order_money - $model['ext_pay']->order_pay_money - $model['ext_pay']->order_use_coupon_money -
                                    $model['ext_pay']->order_use_acc_balance - $model['ext_pay']->order_use_card_money -
                                    $model['ext_pay']->order_use_promotion_money;?>元</h5>
                                <h5 id="pay_info" class="col-sm-12">总金额<?=$model['order']->order_money?>元，线上支付<?=$model['ext_pay']->order_pay_money?>
                                    元，优惠券<?=$model['ext_pay']->order_use_coupon_money ?>元，余额支付<?=$model['ext_pay']->order_use_acc_balance?>
                                    元，服务卡支付<?=$model['ext_pay']->order_use_card_money ?>
                                    元，促销金额<?=$model['ext_pay']->order_use_promotion_money?>元</h5>
                            <?php else:?>
                                <h5 id="must_pay_info" class="col-sm-12">需收取<?=$model['order']->order_money-$model['ext_pop']->order_pop_order_money-$model['ext_pop']->order_pop_operation_money?>元</h5>
                                <h5 id="pay_info" class="col-sm-12">总金额<?=$model['order']->order_money?>元，预付款<?=$model['ext_pop']->order_pop_order_money?>
                                    元，渠道运营费<?=$model['ext_pop']->order_pop_operation_money?>元</h5>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h5 class="col-sm-12" id="order_customer_need">客户需求：<?=$model['ext_customer']->order_customer_need;?></h5>
                            <h5 class="col-sm-12" id="order_customer_memo">客户备注：<?=$model['ext_customer']->order_customer_memo;?></h5>
                            <h5 class="col-sm-12" id="order_cs_memo">客服备注：<?=$model['order']->order_cs_memo;?></h5>
                            <?php if($model['order']->order_is_parent == 1):?>
                            <h5 class="col-sm-12" id="order_check_worker">是否可更换阿姨：<?=$model['ext_flage']->order_flag_check_booked_worker?'是':'否';?></h5>
                            <?php endif;?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel-body">
                <div class="worker-search">
                    <div class="col-sm-4">
                        <input type="text" name="worker_search" class="form-control" id="worker_search_input" placeholder="阿姨姓名或电话...">
                    </div>
                    <div class="col-sm-1">
                        <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>'.Yii::t('app', 'Search'), ['class' => 'btn btn-warning','id'=>'worker_search_submit']) ?>
                    </div>
                    <div class="col-sm-7">
                        <?= Html::button('<span class="glyphicon glyphicon-ban-circle"></span>无法指派', ['class' => 'btn btn-warning','id'=>'can_not_assign']) ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <table id="worker_list" class="table table-bordered">
                    <thead>
                        <tr><th>阿姨姓名</th><th>阿姨电话</th><th>所在店铺</th><th>阿姨身份</th><th>当日订单</th><th>拒单率</th><th>阿姨标签</th><th>接单状态</th><th>操作</th></tr>
                        <?php if($model['order']->orderExtWorker->worker_id>0):?>
                            <tr><td><input type="hidden" value="<?=$model['order']->orderExtWorker->worker_id; ?>" /><?=$model['order']->orderExtWorker->order_worker_name; ?></td>
                                <td><?=$model['order']->orderExtWorker->order_worker_phone;?></td>
                                <td><?=$model['order']->orderExtWorker->order_worker_shop_name;?></td>
                                <td><?=$model['order']->orderExtWorker->order_worker_type_name;?></td>
                                <td> - </td>
                                <td> - </td>
                                <td>已指派阿姨</td>
                                <td id="worker_status_<?=$worker['id']?>">已指派</td>
                                <td id="worker_memo_<?=$worker['id']?>"><a href="javascript:void(0);" class="worker_assign_cancel">取消指派</a></td>
                            </tr>
                        <?php endif;?>
                        <?php foreach($model['booked_workers'] as $worker): ?>
                        <tr><td><input type="hidden" value="<?=$worker['id']?>" /><?=$worker['worker_name']?></td>
                            <td><?=$worker['worker_phone']?></td>
                            <td><?=$worker['shop_name']?></td>
                            <td><?=$worker['worker_identity_description']?></td>
                            <td><?=implode('<br />',$worker['order_booked_time_range']);?></td>
                            <td><?=$worker['worker_stat_order_refuse_percent']?></td>
                            <td><?=$worker['tag']?></td>
                            <td id="worker_status_<?=$worker['id']?>"><?=implode(',',$worker['status']);?></td>
                            <td id="worker_memo_<?=$worker['id']?>"><?=count($worker['memo']) > 0 ? implode(',',$worker['memo']): '
                                <a href="javascript:void(0);" class="worker_assign">派单</a>
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#worker_refuse_modal" class="worker_refuse">拒单</a>
                                <a href="javascript:void(0);" class="worker_contact_failure">未接通</a>';?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="worker_refuse_modal" tabindex="-1" role="dialog" aria-labelledby="worker_refuse_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="worker_refuse_modal_label">请选择拒单原因</h4>
            </div>
            <div class="modal-body">
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo1" value="距离太远"> 距离太远
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo3" value="有其它活儿"> 有其它活儿
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo3" value="0"> <input type="text" class="form-control" id="worker_refuse_memo_other" placeholder="其它" />
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" id="worker_refuse_memo_submit" class="btn btn-warning">确定</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="worker_cancel_modal" tabindex="-1" role="dialog" aria-labelledby="worker_cancel_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="worker_refuse_modal_label">请填写取消指派原因</h4>
            </div>
            <div class="modal-body">
                <div class="radio">
                    <label>
                        <input type="text" class="form-control" id="worker_cancel_memo" placeholder="取消指派原因" />
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" id="worker_cancel_memo_submit" class="btn btn-warning">确定</button>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('/js/order_assign_worker.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
?>

