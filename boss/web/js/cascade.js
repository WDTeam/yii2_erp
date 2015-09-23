$(document).ready(function(){
    $('#province').change(function(){getSonList($(this), 'city', '选择城市')});
    $('#city').change(function(){getSonList($(this), 'county', '选择县（区）')});
    $('#county').change(function(){getSonList($(this), 'town', '选择乡镇（街道）')});
});
function getSonList(obj, targetid, deftext){
    var parent_id = obj.val();
    var url = '/operation-city/get-area';
    var data = {'parent_id':parent_id};
    $.post(url, data, function(t){
        if(t.result == true){
            var str = '<option value="0">'+deftext+'</option>';
            for(var i in t.data){
                str += '<option value="'+i+'">'+t.data[i]+'</option>';
            }
            $('#'+targetid).html(str);
        }else{
            alert('获取数据失败');
        }
    }, 'json');
}