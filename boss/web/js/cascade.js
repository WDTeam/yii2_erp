$(document).ready(function(){
    $('#province').change(function(){getSonList($(this), ['city', 'county', 'town'], '选择城市')});
    $('#city').change(function(){getSonList($(this), ['county', 'town'], '选择县（区）')});
    $('#county').change(function(){getSonList($(this), ['town'], '选择乡镇（街道）')});
});
function getSonList(obj, targetids, deftext){
    var parent_id = obj.val();
    var showend = obj.attr('showend');
    if(showend === 'yes'){return false;}
    if(parent_id > 0){
        var url = '/operation-city/get-area';
        var data = {'parent_id':parent_id};
        $.post(url, data, function(t){
            if(t.result == true){
                var str = '<option value="0">'+deftext+'</option>';
                for(var i in t.data){
                    str += '<option value="'+i+'">'+t.data[i]+'</option>';
                }
                for(var j in targetids){
                    if(j == 0){
                        $('#'+targetids[j]).html(str);
                    }else{
                        var first = $($('#'+targetids[j]).children('option')[0]);
                        $('#'+targetids[j]).html(first);
                    }
                }
            }else{
                alert('获取数据失败');
            }
        }, 'json');
    }
}