$(document).ready(function(){
    $('#operationSpec').change(function(){operationSpec($(this))});

    //验证服务品类下的项目是否重复
    $('#operationgoods-operation_goods_name').focusout(function(){
        validateGoodsRepeat($(this), 'goods');
    });

    $("#operationgoods-operation_category_id").change(function(){
        validateGoodsRepeat($(this), 'category');
    });
});

function operationSpec(obj)
{
/*    index = obj.get(0).selectedIndex;*/
    var value = obj.val();
    if(value){
        var url = '/operation/operation-goods/getspecinfo';
        var data = {'spec_id': value};
        $.post(url, data, function(t){
            $('#SpecInfo').html(t);
        }, 'html');
    }
}

//验证服务品类下的项目是否重复
function validateGoodsRepeat(obj, mark)
{
    var categoryValue = $("#operationgoods-operation_category_id").val();
    var goodsValue = $("#operationgoods-operation_goods_name").val();
    var id = getUrlParam('id');
    var url = '/operation/operation-goods/validate-goods-repeat';
    var data = {'category_id' : categoryValue, 'goods_name' : goodsValue, 'id' : id};

    $.post(url, data, function(data){
        if (data.code == 200) {
            alert(data.errmsg);
            if (mark == 'goods') {
                $("#operationgoods-operation_goods_name").val('');
            }
            if (mark == 'category') {
                $("#operationgoods-operation_category_id").val('');
            }
        } else {
            return true;
        }
    }, 'json');
}

//获取url里的参数
function getUrlParam(name)
{
    //构造一个含有目标参数的正则表达式对象
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");

    //匹配目标参数
    var r = location.search.substr(1).match(reg);

    //返回参数值
    if (r != null) return unescape(r[2]);

    return null;
}
