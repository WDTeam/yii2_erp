<head>
<link href="../web/adminlte/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../web/adminlte/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="../web/adminlte/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="../web/adminlte/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../web/adminlte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />
<script src="../../js/jquery.min.js"></script>
<script src="../../js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../../js/AdminLTE/demo.js" type="text/javascript"></script>
</head>

<?php 
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
?> 

<div>
<aside>
<div>
<section class="content">
    <div class="box-search">
        城市：<input type="text" name="city"/>
        加入时间：<?= \yii\jui\DatePicker::widget(['name' => 'date',]) ?>至<?= \yii\jui\DatePicker::widget(['name' => 'date',]) ?>
        <input type="submit" value="查询"/>
        <!-- <button class="btn btn-info">查询</button> -->
    </div>
    <br/>
<div class="row">
<div class="col-xs-12">
<div class="box">
    <div class="box-header">
        <h3 class="box-title">黑名单列表</h3>
    </div>
    <div class="box-body table-responsive">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" name="customer"/></th>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>电话</th>
                    <th>地址</th>
                    <th>加入黑名单原因</th>
                    <th>加入时间</th>
                    <th>所有订单</th>
                    <th>账户余额</th>
                    <th>投诉</th>
                    <th>评级</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($models as $model){
                ?>
                    <tr>
                        <td><input type="checkbox" name="customer"/></td>
                        <td><?= $model["id"]?></td>
                        <td><?= $model["customer_name"]?></td>
                        <td><?= $model["customer_phone"]?></td>
                        <td><?= $model["customer_live_address_detail"]?></td>
                        <td><?= $model["customer_del_reason"]?></td>
                        <td><?= $model["created_at"]?></td>
                        <td><?= $model["order_num"]?></td>
                        <td><?= $model["customer_balance"]?></td>
                        <td><?= $model["customer_level"]?></td>
                        <td><?= $model["customer_complaint_times"]?></td>
                        <td><?= $model["customer_is_vip"]?></td>
                        <td><?= $model["is_del"]?></td>
                        <td><a href="/customer/cancal_del">取消</></td>
                    </tr>
                <?php 
                }
                ?>
                
            </tbody>
            
        </table>
    </div>
</div>
</div>
</div>
</section>
</aside>
</div>


<script src="../web/adminlte/js/jquery.min.js"></script>
<script src="../web/adminlte/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../web/adminlte/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../web/adminlte/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../web/adminlte/js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../web/adminlte/js/AdminLTE/demo.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $("#example1").dataTable();
        $('#example2').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": false
        });
    });
</script>
    



