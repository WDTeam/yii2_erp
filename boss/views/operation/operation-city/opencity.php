<?php
$this->title = Yii::t('app', 'Opened City').'管理';
?>

<div class="order-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title" style="display: inline-block">开通城市列表<span class="badge"></span></h3>
            <a style="float:right" class="btn btn-primary btn-xs" href="/operation/operation-city/release">上线城市</a>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th>省份</th>
                    <th>城市</th>
                    <th>服务品类</th>
                    <th>服务商品</th>
                    <th>已开通商圈数</th>
                    <th>操作</th>
                </tr>
                <?php foreach((array)$citylist as $key => $value){ ?>
                    <?php foreach((array)$value['citygoodsList'] as $k => $v){ ?>
                    <tr>
                        <th><?= $value['province_name']?></th>
                        <td><?= $value['city_name']?></td>
                        <td><?= $v['operation_category_name']?></td>
                        <td style="padding-top: 0; padding-bottom: 0">

                                <div style="padding-top: 5px">
                                    <?= $v['operation_shop_district_goods_name']?>

                                </div>
                        </td>
                        <td><?= $v['openshodistrictnum']?></td>
                        <td>
                            <a href="<?= Yii::$app->urlManager->createUrl(
                                [
                                    //'/operation/operation-city/settinggoodsinfo',
                                    '/operation/operation-city/addgoods',
                                    'city_id' => $value['city_id'],
                                    'goods_id'=> $v['operation_goods_id'],
                                    'action' => 'editGoods'
                                ]
                                )?>"
                                class="btn btn-success btn-sm">编辑
                            </a>

                            <br>
                        </td>
                    </tr>
                    <?php }?>
                <?php }?>
            </table>
        </div>
    </div>
</div>
