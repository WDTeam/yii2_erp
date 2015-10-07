<div class="panel-heading">
    <h3 class="panel-title"><?= $specvalues['operation_spec_name']?></h3>
    <input type="hidden" name="OperationGoods[operation_spec_name]" value="<?= $specvalues['operation_spec_name']?>">
</div>
<?php foreach((array)$specvalues['info'] as $key => $value){ ?>
    <div class="form-group ">
        <label class="control-label col-md-2"><?= $value['operation_spec_value']?></label>
        <input type="hidden" name="OperationGoods[specinfo][operation_spec_value][]" value="<?= $value['operation_spec_value']?>">
        <div class="col-md-10">
            货号 <?= $value['operation_spec_goods_no']?>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="hidden" name="OperationGoods[specinfo][operation_spec_goods_no][]" value="<?= $value['operation_spec_goods_no']?>">
            市场价格：<input type="text" maxlength="" style="width:50px;" placeholder="市场价格" value="<?= $value['operation_spec_goods_market_price']?>" name="OperationGoods[specinfo][operation_spec_goods_market_price][]" >元
            销售价格：<input type="text" maxlength="" style="width:50px;" placeholder="销售价格" value="<?= $value['operation_spec_goods_sell_price']?>" name="OperationGoods[specinfo][operation_spec_goods_sell_price][]" >元
            成本价格：<input type="text" maxlength="" style="width:50px;" placeholder="成本价格" value="<?= $value['operation_spec_goods_cost_price']?>" name="OperationGoods[specinfo][operation_spec_goods_cost_price][]" >元
            结算价格：<input type="text" maxlength="" style="width:50px;" placeholder="结算价格" value="<?= $value['operation_spec_goods_settlement_price']?>" name="OperationGoods[specinfo][operation_spec_goods_settlement_price][]" >元
            最低消费数量：<input type="text" maxlength="" style="width:70px;" placeholder="最低消费数量" value="<?= $value['operation_spec_goods_lowest_consume_number']?>" name="OperationGoods[specinfo][operation_spec_goods_lowest_consume_number][]" >
            <div class="col-lg-6">
                佣金收取方式：
                <div class="input-group">
                  <span class="input-group-addon">
                      百分比
                    <input name="OperationGoods[specinfo][operation_spec_goods_commission_mode][<?= $key?>]" <?php if($value['operation_spec_goods_commission_mode'] == 1){ ?>checked="checked" <?php }?> type="radio" value="1">
                  </span>
                    <input type="text" name="OperationGoods[specinfo][operation_spec_goods_commissionpercent][<?= $key?>]" class="form-control" value="<?php if($value['operation_spec_goods_commission_mode'] == 1){ echo $value['operation_spec_goods_commission']; }?>" aria-label="..." style="width:60px;" placeholder="百分比">
                </div>
                <div class="input-group">
                  <span class="input-group-addon">
                      金额
                    <input name="OperationGoods[specinfo][operation_spec_goods_commission_mode][<?= $key?>]" <?php if($value['operation_spec_goods_commission_mode'] == 2){ ?>checked="checked" <?php }?> type="radio" value="2">
                  </span>
                    <input type="text" name="OperationGoods[specinfo][operation_spec_goods_commissiondigital][<?= $key?>]" class="form-control" value="<?php if($value['operation_spec_goods_commission_mode'] == 2){ echo $value['operation_spec_goods_commission']; }?>" aria-label="..." style="width:74px;"  placeholder="金额">
                </div>
            </div>
        </div>
        <div class="col-md-offset-2 col-md-10"></div>
        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
    </div>
<?php }?>