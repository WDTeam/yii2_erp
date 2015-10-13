<div class="order-index">
    <div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">开通城市列表：<span class="badge"></span></h3></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th>省份</th>
                    <th>城市名称</th>
                    <th>服务</th>
                </tr>
                <?php foreach((array)$citylist as $key => $value){ ?>
                    <tr>
                        <th><?= $value['province_name']?></th>
                        <td><?= $value['city_name']?></td>
                        <td>
                            <?php foreach((array)$value['citygoodsList'] as $k => $v){ ?>
                                    <div>
                                        <?= $v['operation_shop_district_goods_name']?>
                                        开通商圈：<?= $v['openshodistrictnum']?>
                                        <a href="<?= Yii::$app->urlManager->createUrl(['operation-city/settinggoodsinfo','city_id' => $value['city_id'], 'goods_id'=> $v['operation_goods_id'], 'cityAddGoods' => 'editGoods'])?>">编辑</a>
                                        
                                        <br>
                                    </div>
                            <?php }?>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>