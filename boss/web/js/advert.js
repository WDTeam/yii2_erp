$(document).on('change', '#operationadvertposition-operation_platform_id', function(){getPlatformVersion($(this));});

function getPlatformVersion(obj){
    var platform_id = obj.val();
    if(platform_id != 0 && platform_id != ''){
        $.post('/operation-platform-version/version-list', {'platform_id':platform_id}, function(t){
            if(t.result){
                var str = '<option value="0">选择版本</option>';
                for(var i in t.data){
                    str += '<option value="'+i+'">'+t.data[i]+'</option>';
                }
                $('#operationadvertposition-operation_platform_version_id').html(str);
                $('#operationadvertposition-operation_platform_version_id').parent().removeClass('hide');
            }else{
                $('#operationadvertposition-operation_platform_version_id').parent().addClass('hide');
            }
        }, 'json');
    }
}