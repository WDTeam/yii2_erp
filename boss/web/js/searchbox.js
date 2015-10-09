$(document).on('click', '#ajax_submit_search', function(){SubmitSearch($(this))});

function SubmitSearch(obj){
    var url = obj.prev().attr('href');
    var data = {'fields':obj.parent().parent().find('select').val(), 'keyword':obj.parent().parent().find('input[type=text]').val()};
    var o = $('#seachBox_addons').children('input[type=hidden]');
    var callback = $('#seachBox_addons').attr('callback');
    var len = o.length;
    for(var i = 0; i < len; i++){
        data[$(o[i]).attr('name')] = $(o[i]).val();
    }
    $.post(url, data, function(t){
        eval(callback+'(t)');
    }, 'html');
}