<?php
return [
'uploadpath' =>true, //true上传到七牛 false 上传的本地
'worker_base_salary'=>3000,//阿姨的底薪
'unit_order_money_nonself_fulltime' =>50,//小家政全时段阿姨补贴的每单的金额
'order_count_per_week'=>12,//小家政全时段阿姨的底薪策略是保单，每周12单
    'order'=>[
        'MANUAL_ASSIGN_lONG_TIME'=>900,
        'ORDER_BOOKED_WORKER_ASSIGN_TIME'=>900,
        'ORDER_FULL_TIME_WORKER_SYS_ASSIGN_TIME'=>300,
        'ORDER_PART_TIME_WORKER_SYS_ASSIGN_TIME'=>900,
    ]
];
