

$(document).on('click','td',function(){
    //console.log($(this).hasClass('success'));
    if($(this).hasClass('success')==true){
        $(this).removeClass('success');
    }else{
        $(this).addClass('success');
    }
});

$(document).on('change','input[type=checkbox]',function(){
    if($(this).is(':checked')){
        $(this).parent().siblings('td').attr('class','success')
    }else{
        $(this).parent().siblings('td').removeClass('success')
    }
});
$(document).on('click','.close',function(){
    $(this).parents('.schedule_content').remove();
});
$('#btn-add').on('click',function(){
    var date_range = $('input[name=date_range]').val();
    if(!date_range){
        alert('请选择时间');
        return false;
    }
    var schedule_date = '<div date="'+date_range+'" class="schedule-date">工作日期：'+date_range+'<button type="button" class="close"  style="color:red;font-size:14px;margin-top:3px;margin-left: 3px">删除</button></div>';
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
        $(this).find('tr').each(function(){
            schedule_weekday = $(this).children('th').attr('weekday');
            timeline_arr = {};
            i=0;
            $(this).children('td').each(function(){
                if($(this).hasClass('success')){
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