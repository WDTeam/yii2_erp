/**
 * @introduction 人工派单
 * @author zhangrenzhao
 * @date 2015-11-06
 * @type {number}
 */
var d=0;
var t;
var t2;
var c;
var c1;
var c2;
var r;
var ss =parseInt(15*60);
var mm;
var rr;
var downtime;
var downtime15;
//start.......................
$(document).ready(function(){
   //初始化界面控件
   $('#startId').show();
   $('#waitId').hide();
   $('#endId').hide();
   $('#dispatchId').hide();
   $('#restId').hide();
   $('#acceptId').hide();
   $('#restAcceptId').hide();
   $('#restEndId').hide();
   //初始化ID
   initId();
   //初始化页面
   //initPage();
   //秒换算为天、时、分、秒
    agvChange();
   //初始化今日派单
    initCount();
   //更新任务状态
   dispStatus();
   //单击开工啦
   $('#startId').click(function(){startWork();});
   //单击系统派单成功
   $('#waitId').click(function(){waitWork();});
   //单击指派成功
   $('#dispatchedId').click(function(){dispatchWork();});
   //单击无法指派
   $('#nonDispatchId').click(function(){nonDispatchWork();});
   //单击我要接活
   $('#acceptId').click(function(){acceptWork();});
   //单击小休
   $('#restId').click(function(){restWork();});
   //单击我要接活（小休时）
   $('#restAcceptId').click(function(){restAcceptWork();});
   //单击收工啦
   $('#endId').click(function(){endWork();});
   //单击收工（小休时）
   $('#restEndId').click(function(){restEndWork();});

});
//因第一次进入页面id为空，当天客服关闭页面，在此进入时，id不再为空
function initId(){
   var idd=$("#idd").val();
   //alert("idd:"+idd);
   if(isNaN(idd)|idd==''){
      return;
   }else{
     // alert("id1::"+id);
      $("#id").text(idd);
   }
}

//调用后台，保存参数
function saveParams(){

   var id=parseInt($('#id').text());
  // alert("id2::"+id);
   if(isNaN(id)){
      id='';
   }
   var  dispatcher_kpi_free_time=parseInt($('#free_time').val());
   var  dispatcher_kpi_busy_time=parseInt($('#busy_time').val());
   var  dispatcher_kpi_rest_time=parseInt($('#rest_time').val());
   var  dispatcher_kpi_obtain_count=parseInt($('#obtain_count').text());
   var  dispatcher_kpi_assigned_count=parseInt($('#assigned_count').text());
   var  dispatcher_kpi_assigned_rate=parseFloat($('#assigned_rate').text());
   var  dispatcher_kpi_status=parseInt($('#newStatusId').text());
   //alert("dispatcher_kpi_free_time:"+dispatcher_kpi_free_time);
   //alert("dispatcher_kpi_busy_time:"+dispatcher_kpi_busy_time);
   //alert("dispatcher_kpi_rest_time:"+dispatcher_kpi_rest_time);
   //alert("dispatcher_kpi_obtain_count:"+dispatcher_kpi_obtain_count);
   //alert("dispatcher_kpi_assigned_count:"+dispatcher_kpi_assigned_count);
   var params = {
      flag:2,
      id:id,
      dispatcher_kpi_free_time:dispatcher_kpi_free_time,
      dispatcher_kpi_busy_time:dispatcher_kpi_busy_time,
      dispatcher_kpi_rest_time:dispatcher_kpi_rest_time,
      dispatcher_kpi_obtain_count:dispatcher_kpi_obtain_count,
      dispatcher_kpi_assigned_count:dispatcher_kpi_assigned_count,
      dispatcher_kpi_assigned_rate:dispatcher_kpi_assigned_rate,
      dispatcher_kpi_status:dispatcher_kpi_status,
   };

   $.ajax({
      type: "GET",
      url: "/order/order-dispatcher-kpi",
      data: params,
      dataType:'text',
      success:function(msg){
         $('#id').text(msg);
         //alert("msg:"+msg);
      }
   });
}
//初始化控件
function initPage(){
   var status= $('#dispatcher_kpi_status').val();
   if(status==1){//空闲界面
      startWork();
   }else if(status==2){//忙碌界面
      waitWork();
   }else if(status==3){//小休界面
      restWork();
   }else{//小休，停工
      restEndWork();
   }

}
//初始化今日派单
function initCount(){
   //平均值换算
   var obtainId= $('#obtainId').val();
   var assignedId= $('#assignedId').val();
   var assRateId= $('#assRateId').val();
   if(obtainId==''){
      $('#obtain_count').text(0);
   }else{
      $('#obtain_count').text(obtainId);
   }
   if(assignedId==''){
      $('#assigned_count').text(0);
   }else{
      $('#assigned_count').text(assignedId);
   }
   if(assRateId==''){
      $('#assigned_rate').text(0);
   }else{
      $('#assigned_rate').text(assRateId);
   }
}
//更新今日派单
function updateCount(c){
   var  dispatcher_kpi_obtain_count=parseInt($('#obtain_count').text());
   var  dispatcher_kpi_assigned_count=parseInt($('#assigned_count').text());
   if(c==1){//更新获单数量
      dispatcher_kpi_obtain_count=dispatcher_kpi_obtain_count+1;
      $('#obtain_count').text(dispatcher_kpi_obtain_count);
   }else{//更新已派单数量
      dispatcher_kpi_assigned_count=dispatcher_kpi_assigned_count+1;
      $('#assigned_count').text(dispatcher_kpi_assigned_count)
   }
   //更新派单成功率
   if(dispatcher_kpi_obtain_count!=0){
      $('#assigned_rate').text((dispatcher_kpi_assigned_count/dispatcher_kpi_obtain_count).toFixed(2))
   }
}
//重新进入界面，待开工开始
function dispStatus(){
   $('#statusId').text("待开工");
   $('#newStatusId').text(0);
   /*var status= $('#dispatcher_kpi_status').val();
   if(status==0){//待开工
      $('#statusId').text("待开工");
      $('#newStatusId').text(0);
   }else if(status==1){//空闲
      $('#statusId').text("空闲");
      $('#newStatusId').text(1);
   }else if(status==2){//忙碌
      $('#statusId').text("忙碌");
      $('#newStatusId').text(2);
   }else{//收工
      $('#statusId').text("收工");
      $('#newStatusId').text(3);
   }*/
}
//单击收工啦(小休时)
function restEndWork(){
   saveParams();
   //停止小休时间计算
   stopCount()
   //保存忙碌时间
   $('#rest_time').val(d);

   //调用后台，更新当日小休时间和状态3==============================
   saveParams();

   //初始化状态
   $('#statusId').text("收工");
   $('#dispatcher_kpi_status').val(3)
   $('#newStatusId').text(3);
   return;
}
//单击收工啦
function endWork(){
   //停止忙碌时间计算
   stopCount()
   //保存忙碌时间
   $('#busy_time').val(d);
   //调用后台，更新当日忙碌时间和状态3==============================
   saveParams();
   //初始化状态
   $('#statusId').text("收工");
   $('#dispatcher_kpi_status').val(3)
   $('#newStatusId').text(3);
   //初始化10秒倒计时
   stopCount10();
   return;

}
//单击我要接活(小休时)
function restAcceptWork(){
   //停止忙碌时间计算
   stopCount()
   //保存小休时间
   $('#rest_time').val(d);
   //调用后台，更新当日忙碌时间和状态2==============================
   saveParams();
   //更新状态
   $('#statusId').text("空闲");
   $('#dispatcher_kpi_status').val(1)
   $('#newStatusId').text(1);
   //初始化计时参数
   d=0;t=0;c=0;c1=0;c2=0;r=0;

   //获得界面空闲时
   d=Number($('#free_time').val());

   //初始化按钮
   $('#waitId').show();
   $('#endId').hide();
   $('#dispatchId').hide();
   $('#restId').hide();
   $('#acceptId').hide();
   $('#restAcceptId').hide();
   $('#restEndId').hide();

   //计算空闲时间
   freeTimedCount();
}
//单击小休
function restWork(){
   //停止忙碌时间计算
   stopCount();
   //保存忙碌时间
   $('#busy_time').val(d);
   //调用后台，更新当日忙碌时间和状态2==============================
   saveParams();
   //更新状态
   $('#statusId').text("小休");
   $('#dispatcher_kpi_status').val(2)
   $('#newStatusId').text(2);
   //初始化计时参数
   d=0;t=0;c=0;c1=0;c2=0;r=0;

   //获得界面忙碌时
   d=Number($('#rest_time').val());

   //初始化按钮
   stopCount10();
   $('#waitId').hide();
   $('#endId').hide();
   $('#dispatchId').hide();
   $('#restId').hide();
   $('#acceptId').hide();
   $('#restEndId').show();
   $('#restAcceptId').show();

   //计算忙碌时间
   restTimedCount();
}
//单击我要接活
function acceptWork(){
   //停止忙碌时间计算
   stopCount();
   //保存忙碌时间
   $('#busy_time').val(d);
   //调用后台，更新当日忙碌时间和状态2==============================
   saveParams();
   //更新状态
   $('#statusId').text("空闲");
   $('#dispatcher_kpi_status').val(1)
   $('#newStatusId').text(1);
   //初始化计时参数
   d=0;t=0;c=0;c1=0;c2=0;r=0;
   //获得界面忙碌时
   d=Number($('#free_time').val());

   //初始化按钮
   stopCount10();
   $('#waitId').show();
   $('#endId').hide();
   $('#dispatchId').hide();
   $('#restId').hide();
   $('#acceptId').hide();
   $('#restAcceptId').hide();
   $('#restEndId').hide();

   //计算空闲时间
   freeTimedCount();
}
//单击无法指派
function nonDispatchWork(){
   //10秒倒计时
   downtime=setInterval("rundown();", 1000);
   //初始化15分钟倒计时
   stopCount15();
   //初始化按钮
   $('#dispatchId').hide();
   $('#startId').hide();
   $('#endId').show();
   $('#restId').show();
   $('#acceptId').show();
   $('#restAcceptId').hide();
}
//单击人工派单成功
function dispatchWork(){
   //派单成功加1
   updateCount(2);
   //调用后台，更新指派成功数量===============================
   saveParams();
   //10秒倒计时
   downtime=setInterval("rundown();", 1000);
   //初始化15分钟倒计时
   stopCount15();
   //初始化按钮
   $('#dispatchId').hide();
   $('#startId').hide();
   $('#endId').show();
   $('#restId').show();
   $('#acceptId').show();
   $('#restAcceptId').hide();
}
//系统指派成功，等待客服人工派单
function waitWork() {
   //停止空闲时间计算
   stopCount()
   //保存空闲时间
   $('#free_time').val(d);
   //更新应派单数
   updateCount(1);
   //调用后台，更新当日空闲时间和获得指派单数量===============================
   saveParams();
   //更新状态
   $('#statusId').text("忙碌");
   $('#dispatcher_kpi_status').val(2)
   $('#newStatusId').text(2);
   //初始化计时参数
   d=0;t=0;c=0;c1=0;c2=0;r=0;
   //15分钟倒计时
   downtime15=setInterval("rundown15();", 1000);

   //初始化按钮
   $('#waitId').hide();
   $('#dispatchId').show();
   $('#restAcceptId').hide();
   //获得界面忙碌时
   d=Number($('#busy_time').val());
   //计算忙碌时间
   busyTimedCount();
}

//单击开工啦
function startWork() {
   //alert("start");
   //初始化状态
   $('#statusId').text("空闲");
   $('#dispatcher_kpi_status').val(1)
   $('#newStatusId').text(1);
   //调用后台，创建当日记录========================================
   saveParams();
   //初始化按钮
   $('#startId').hide();
   $('#waitId').show();
   //获得界面空闲时间
   d=Number($('#free_time').val());
   //计算空闲时间
   freeTimedCount();
}
//空闲时间计算器
function restTimedCount() {
   r=agvTimeChange(d);
   $("#rest").text(r);
   d=d+1;
   t=setTimeout("restTimedCount()",1000);
}
//空闲时间计算器
function freeTimedCount() {
   r=agvTimeChange(d);
   $("#free").text(r);
   d=d+1;
   t=setTimeout("freeTimedCount()",1000);
}
//忙碌时间计算器
function busyTimedCount() {
   r=agvTimeChange(d);
   $("#busy").text(r);
   d=d+1;
   t=setTimeout("busyTimedCount()",1000);
}
//小休时间计算器
function restTimedCount() {
   r=agvTimeChange(d);
   $("#rest").text(r);
   d=d+1;
   t=setTimeout("restTimedCount()",1000);
}

//秒换算时分秒
function agvTimeChange(d){
   c=d;
   if(c > 60) {
      c1 = parseInt(c/60);
      c = parseInt(c%60);
      if(c1 > 60) {
         c2 = parseInt(c1/60);
         c1 = parseInt(c1%60);
      }
   }
   r = ""+parseInt(c)+"秒";
   if(c1 > 0) {
      r = ""+parseInt(c1)+"分"+r;
   }
   if(c2 > 0) {
      r = ""+parseInt(c2)+"小时"+r;
   }
   return r;
}
//进入页面，时间换算
function agvChange(){
   //平均值换算
   var free_time_avg= $('#free_time_avg').val();
   var busy_time_avg= $('#busy_time_avg').val();
   var rest_time_avg= $('#rest_time_avg').val();
   if(free_time_avg==''){
      $('#free_avg').text("0秒");
   }else{
      $('#free_avg').text(agvTimeChange(free_time_avg));
   }
   if(busy_time_avg==''){
      $('#busy_avg').text("0秒");
   }else{
      $('#busy_avg').text(agvTimeChange(busy_time_avg));
   }
   if(rest_time_avg==''){
      $('#rest_avg').text("0秒");
   }else{
      $('#rest_avg').text(agvTimeChange(rest_time_avg));
   }

   //今日换算
   d=0;t=0;c=0;c1=0;c2=0;r=0;
   var free_time= $('#free_time').val();
   var busy_time= $('#busy_time').val();
   var rest_time= $('#rest_time').val();
   if(free_time==''){
      $('#free').text("0秒");
   }else{
      $('#free').text(agvTimeChange(free_time));
   }
   if(busy_time==''){
      $('#busy').text("0秒");
   }else{
      $('#busy').text(agvTimeChange(busy_time));
   }
   if(rest_time==''){
      $('#rest').text("0秒");
   }else{
      $('#rest').text(agvTimeChange(rest_time));
   }

}
//停止时间控件
function stopCount() {
   clearTimeout(t)
}
//10秒倒计时
function rundown(){
 var s = document.getElementById("rundownId");
 if(s.innerHTML == 0){
    stopCount10();
    //进入接活状态
    acceptWork();
    return false;
 }
 s.innerHTML = s.innerHTML * 1 - 1;
}
//停止10秒倒计时
function stopCount10() {
   clearTimeout(downtime);
   $('#rundownId').text(10);
}
//15分钟倒计时
function rundown15(){
   var s = document.getElementById("rundown15Id");
   var sN = document.getElementById("rundown15Name");
   if(s.innerHTML == 0){
      stopCount15();
      //超时，指派失败，记录失败原因===================

      return false;
   }
   maxtimeee=s.innerHTML;
   if(maxtimeee>=0) {
      minutess = Math.floor(maxtimeee / 60);
      secondss = Math.floor(maxtimeee % 60);
      dd = "" + parseInt(secondss) + "秒";
      if (minutess > 0) {
         dd = "" + parseInt(minutess) + "分" + dd;
      }
      sN.innerHTML=dd;
   }
   s.innerHTML = s.innerHTML * 1 - 1;
}
//停止15分钟倒计时
function stopCount15() {
   clearTimeout(downtime15);
   document.getElementById("rundown15Name").innerHTML="15分0秒";
   $('#rundown15Id').text(15*60);
}

