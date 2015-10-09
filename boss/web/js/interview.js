$(document).ready(function(){
    $('.fire').click(function(){fire($(this));});
    $('.sendsms').click(function(){sendSms($(this));});
    $('.online').click(function(){onlineExam($(this));});
    $('.operscore').click(function(){operscore($(this));});
    $('.testwk').click(function(){testwk($(this));});
    $('.saveOrders').click(function(){saveOrders($(this));});
    $('.showImg').click(function(){openImg($(this));});
});

function openImg(obj){
    var img = obj.children('img').attr('src');
    $.win.open('图片预览', '', 'pic', img);
}

function saveOrders(obj){
    var cid = obj.attr('cid');
    var direction = obj.attr('direction');
    var data = {direction:direction};
    var url = '/courseware/saveorders/'+cid;
    $.post(url, data, function(t){
        if(t.result === false){
            alert(t.msg);
        }else{
            window.location.reload();
        }
    }, 'json');
}

function saveTest(){
    var tr = $("input[name='test_result']:checked").val();
    var ts = $("#test_situation").val();
    var uid = $("#test_situation").attr('uid');
    var url = '/interview/savetest/'+uid;
    $.post(url, {tr:tr, ts:ts}, function(t){
        if(t = 'success'){window.location.reload();}
    });
}

function testwk(obj){
    var uid = obj.attr('uid');
    var title = '实操考试操作';
    var url = '';
    var html = '<div class="rlist">';
    html += '<label><input name="test_situation" class="form-control" placeholder="投诉数量" id="test_situation" type="text" uid="'+uid+'" /></label>';
    html += '<div class="clearfix"></div>';
    html += '</div>';
    html += '<div class="rlist">';
    html += '<label><input name="test_result" id="test_result1" type="radio" value="1" uid="'+uid+'"  />试工通过</label>';
    html += '<label><input name="test_result" id="test_result2" type="radio" value="2" uid="'+uid+'"  />试工不通过</label>';
    html += '<div class="clearfix"></div>';
    html += '</div>';
    var data={}
    data.html = html;
    data.yesFunc = 'saveTest';
    $.win.open(title, url, 'confirm', data);
}

function openWin_yes(){
    var ep = $("input[name='exampass']:checked").val();
    var uid =  $("input[name='exampass']:checked").attr('uid');
    var tw = $("input[name='testwork']:checked").val();
    var url = '/interview/testwork/'+uid;
    $.post(url, {ep:ep, tw:tw}, function(t){
        if(t = 'success'){window.location.reload();}
    });
}

function operscore(obj){
    var uid = obj.attr('uid');
    var title = '实操考试操作';
    var url = '';
    var operation_score = obj.attr('operation_score');
    
    var test_status = obj.attr('test_status');
    var html = '<div class="rlist">';
    if(operation_score == '1'){
        html += '<label><input name="exampass" id="exampass1" type="radio" value="1" uid="'+uid+'" checked="checked" />考试通过</label>';
        html += '<label><input name="exampass" id="exampass2" type="radio" value="2" uid="'+uid+'"  />考试未通过</label>';
    }else if(operation_score == '2'){
        html += '<label><input name="exampass" id="exampass1" type="radio" value="1" uid="'+uid+'" />考试通过</label>';
        html += '<label><input name="exampass" id="exampass2" type="radio" value="2" uid="'+uid+'" checked="checked"  />考试未通过</label>';
    }else{
        html += '<label><input name="exampass" id="exampass1" type="radio" value="1" uid="'+uid+'" />考试通过</label>';
        html += '<label><input name="exampass" id="exampass2" type="radio" value="2" uid="'+uid+'" />考试未通过</label>';
    }
    html += '<div class="clearfix"></div>';
    html += '</div>';
    html += '<div class="rlist">';
    if(test_status == '1'){
        html += '<label><input name="testwork" id="testwork1" type="radio" value="1" uid="'+uid+'" checked="checked"  />安排试工</label>';
        html += '<label><input name="testwork" id="testwork2" type="radio" value="2" uid="'+uid+'"  />不安排试工</label>';
    }else if(test_status == '2'){
        html += '<label><input name="testwork" id="testwork1" type="radio" value="1" uid="'+uid+'"  />安排试工</label>';
        html += '<label><input name="testwork" id="testwork2" type="radio" value="2" uid="'+uid+'" checked="checked"  />不安排试工</label>';
    }else{
        html += '<label><input name="testwork" id="testwork1" type="radio" value="1" uid="'+uid+'"  />安排试工</label>';
        html += '<label><input name="testwork" id="testwork2" type="radio" value="2" uid="'+uid+'"  />不安排试工</label>';
    }
    html += '<div class="clearfix"></div>';
    html += '</div>';
    var data={}
    data.html = html;
    data.yesFunc = 'openWin_yes';
    $.win.open(title, url, 'confirm', data);
}

function onlineExam(obj){
    var mode = obj.attr('mode');
    var uid = obj.attr('uid');
    var url = '/interview/examxc/'+uid;
    $.post(url, {mode:mode}, function(t){
        if(t = 'success'){window.location.reload();}
    });
}

function fire(obj){
    if(confirm('您确认立即与这个阿姨解约吗？')){
        var id = obj.attr('uid');
        var url = '/signed/delete/'+id;
        $.post(url, {}, function(t){
            if(t = 'success'){window.location.reload();}
        });
    }
}

function sendSms(obj){
    var uid = obj.attr('uid');
    var title = '发送面试通知';
    var url = '';
    var html = '<div class="rlist">';
    html += '<label><input name="faceExamDate" placeHolder="填写面试时间" id="faceExamDate" type="text" value="" uid="'+uid+'" /></label>';
    html += '<label>';
    html += '<select name="faceExamTime" id="faceExamTime" uid="'+uid+'">';
    html += '<option value="">选择面试时间</option>';
    html += '<option value="8:00">8:00</option>';
    html += '<option value="8:00">8:30</option>';
    html += '<option value="9:00">9:00</option>';
    html += '<option value="9:00">9:30</option>';
    html += '<option value="10:00">10:00</option>';
    html += '<option value="10:00">10:30</option>';
    html += '<option value="11:00">11:00</option>';
    html += '<option value="11:00">11:30</option>';
    html += '<option value="12:00">12:00</option>';
    html += '<option value="12:00">12:30</option>';
    html += '<option value="13:00">13:00</option>';
    html += '<option value="13:00">13:30</option>';
    html += '<option value="14:00">14:00</option>';
    html += '<option value="14:00">14:30</option>';
    html += '<option value="15:00">15:00</option>';
    html += '<option value="15:00">15:30</option>';
    html += '<option value="16:00">16:00</option>';
    html += '<option value="16:00">16:30</option>';
    html += '<option value="17:00">17:00</option>';
    html += '<option value="17:00">17:30</option>';
    html += '<option value="18:00">18:00</option>';
    html += '<option value="18:00">18:30</option>';
    html += '<option value="19:00">19:00</option>';
    html += '<option value="19:00">19:30</option>';
    html += '</select>';
    html += '</label>';
    html += '<div class="clearfix"></div>';
    html += '</div>';
    html += '<div class="rlist">';
    html += '<label>';
    html += '<select name="faceExamPlace" id="faceExamPlace">';
    html += '<option value="">选择面试地点</option>';
    html += '<option value="朝阳区青年路华纺易城10号楼4门101">朝阳区青年路华纺易城10号楼4门101</option>';
    html += '<option value="朝阳区双井九龙花园2号楼B座112">朝阳区双井九龙花园2号楼B座112</option>';
    html += '<option value="丰台区草桥欣园区7号楼4单元101">丰台区草桥欣园区7号楼4单元101</option>';
    html += '<option value="望京国风北京（望京国际商业中心站）8单元609号楼103室 ">望京国风北京（望京国际商业中心站）8单元609号楼103室 </option>';
    html += '<option value="海淀区远大二区1号楼1b1e">海淀区远大二区1号楼1b1e</option>';
    html += '<option value="天通北苑一区10号楼5单元101">天通北苑一区10号楼5单元101</option>';
    html += '<option value="通州果园金源泉小区8号楼1单元102室">通州果园金源泉小区8号楼1单元102室</option>';
    html += '</select>';
    html += '</label>';
    html += '<div class="clearfix"></div>';
    html += '</div>';
    var data={}
    data.html = html;
    data.yesFunc = 'smsSend';
    $.win.open(title, url, 'confirm', data);
}

function smsSend(){
        var uid = $('#faceExamTime').attr('uid');
        var faceExamDate = $('#faceExamDate').val();
        var faceExamTime = $('#faceExamTime').val();
        var faceExamPlace = $('#faceExamPlace').val();
        if(faceExamDate == ''){
            alert('请您填写面试日期，日期格式：2015-08-08');
            $('#faceExamDate').focus();
            return false;
        }else if(!faceExamDate.match(/^(\d{4})-(0\d{1}|1[0-2])-(0\d{1}|[12]\d{1}|3[01])$/)){
            alert('面试日期格式错误，正确格式：2015-08-08');
            $('#faceExamDate').select();
            return false;
        }
        if(faceExamTime == ''){
            alert('请您选择面试时间！');
            $('#faceExamTime').focus();
            return false;
        }
        if(faceExamPlace == ''){
            alert('请您选择面试地点！');
            $('#faceExamPlace').focus();
            return false;
        }
    if(confirm('您确认要短信通知阿姨面试吗？')){
        var url = '/interview/sendsms/'+uid;
        var data = {fed:faceExamDate,fet:faceExamTime,fep:faceExamPlace};
        $.post(url, data, function(t){
            if(t == 'success'){
                alert('短信通知成功！');
                window.location.reload();
            }
        });
    }
    
}

//function sendSms(obj){
//    if(confirm('您确认要短信通知阿姨面试吗？')){
//        var id = obj.attr('uid');
//        var url = '/interview/sendsms/'+id;
//        $.post(url, {}, function(t){
//            if(t == 'success'){
//                alert('短信通知成功！');
//                window.location.reload();
//            }
//        });
//    }
//}