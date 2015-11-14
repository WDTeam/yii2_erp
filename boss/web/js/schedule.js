

$(document).on('click','td',function(){
    //console.log($(this).hasClass('success'));

    if($(this).hasClass('select')==true){
        $(this).removeClass('select');
    }else if($(this).hasClass('actives')==true){
        $(this).removeClass('actives');
    }else{
        $(this).addClass('select');
    }
});
$(document).on('click','.switch',function(){
    if($(this).attr('show_type')=='schedule-for-mysql'){
        $(this).parents('.schedule_content').children('.schedule-info-redis').show();
        $(this).parents('.schedule_content').children('.schedule-info').hide();
        $(this).attr('show_type','schedule-for-redis');
        $(this).text('切换到数据库');
    }else{
        $(this).parents('.schedule_content').children('.schedule-info-redis').hide();
        $(this).parents('.schedule_content').children('.schedule-info').show();
        $(this).attr('show_type','schedule-for-mysql');
        $(this).text('切换到缓存');
    }
    console.log($(this).attr('show_type'));
})
$(document).on('change','input[type=checkbox]',function(){
    if($(this).parent().siblings('td').hasClass('danger')){
        return false;
    }
    if($(this).is(':checked')){
        $(this).parent().siblings('td').attr('class','select')
    }else{
        $(this).parent().siblings('td').removeClass('select')
    }
});
$(document).on('click','.delete',function(){
    $(this).parents('.schedule_content').remove();
});
$(document).on('click','.applyBtn',function(){
    /*
     留个验证时间不能重复的口
     */
    var date_range = $('input[name=date_range]').val();
    if(!date_range){
        alert('请选择时间');
        return false;
    }
    var schedule_date = '<div date="'+date_range+'" class="schedule-date">工作日期：'+date_range+
        '  <button type="button" class="delete btn btn-xs btn-danger" >删除</button></div>';
    var schedule_template = $('#schedule-template').html();
    var schedule_content = '<div class="schedule_content">'+schedule_date+schedule_template+'</div>';
    $('#schedule-list').append(schedule_content);
});

$('#btn-submit').on('click',function(){
    //console.log($('.schedule_content').serializeArray());
    schedule_arr = {};
    $('.schedule_content').each(function(index){
        schedule_date = $(this).children('.schedule-date').attr('date');
        weekday_arr = {};
        $(this).children('.schedule-info').find('tr').each(function(){
            schedule_weekday = $(this).children('th').attr('weekday');
            timeline_arr = {};
            i=0;
            $(this).children('td').each(function(){
                if($(this).hasClass('select')){
                    timeline_arr[i] = $(this).html();
                    i++;
                }
                if($(this).hasClass('actives')){
                    timeline_arr[i] = $(this).html();
                    i++;
                }
            });
            weekday_arr[schedule_weekday] = timeline_arr;
        });
        schedule_arr[schedule_date] = weekday_arr;
        //console.log(schedule_date);
        //schedule_arr['2015-03'] = weekday_arr;
    });
    schedule_json = JSON.stringify(schedule_arr);
    $('input[name=schedule_data]').val(schedule_json);
    $('#form').submit();
});
$(document).on('onload',function(){
    $('.applyBtn').html('添加时间');
})
