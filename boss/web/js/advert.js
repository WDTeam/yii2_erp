$(document).on('change', '#operationadvertposition-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertposition-operation_platform_version_id');});
$(document).on('change', '#operationadvertcontent-operation_platform_id', function(){getPlatformVersion($(this), 'operationadvertcontent-operation_platform_version_id');});
function getPlatformVersion(obj, sourceid){
    var platform_id = obj.val();
    if(platform_id != 0 && platform_id != ''){
        $.post('/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
            if(t.result){
                var str = '<option value="0">选择版本</option>';
                for(var i in t.data){
                    str += '<option value="'+i+'">'+t.data[i]+'</option>';
                }
                $('#'+sourceid).html(str);
                $('#'+sourceid).parent().removeClass('hide');
            }else{
                $('#'+sourceid).parent().addClass('hide');
            }
        }, 'json');
    }
}