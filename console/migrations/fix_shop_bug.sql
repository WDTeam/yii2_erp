UPDATE `ejj_shop` AS a
LEFT JOIN `ejj_shop_manager` AS b ON a.`shop_manager_id`=b.`id`
SET a.province_id=b.`province_id`, a.`city_id`=b.`city_id`,a.`county_id`=b.`county_id`;
/*[15:38:30][0 ms]*/ EXPLAIN UPDATE `ejj_shop` SET audit_status=1; 
/*[15:44:06][27 ms]*/ UPDATE `ejj_shop` SET `province_id` = '440000' , `city_id` = '440300' , `county_id` = '440303' WHERE `id` = '212'; 
/*[15:44:08][3 ms]*/ UPDATE `ejj_shop` SET `province_id` = '310000' WHERE `id` = '213'; 
/*[15:44:09][2 ms]*/ UPDATE `ejj_shop` SET `province_id` = '310000' WHERE `id` = '214'; 
/*[15:44:11][2 ms]*/ UPDATE `ejj_shop` SET `province_id` = '310000' WHERE `id` = '215'; 
/*[15:44:45][8 ms]*/ UPDATE `ejj_shop` SET `province_id` = '310000' WHERE `id` = '216'; 
/*[15:45:16][9 ms]*/ UPDATE `ejj_shop` SET `city_id` = '310115' WHERE `id` = '213'; 
/*[15:45:32][3 ms]*/ UPDATE `ejj_shop` SET `city_id` = '310105' WHERE `id` = '214'; 
/*[15:45:53][8 ms]*/ UPDATE `ejj_shop` SET `city_id` = '310104' WHERE `id` = '215'; 
/*[15:46:05][8 ms]*/ UPDATE `ejj_shop` SET `city_id` = '310110' WHERE `id` = '216'; 
/*[15:46:07][2 ms]*/ UPDATE `ejj_shop` SET `county_id` = '0' WHERE `id` = '214'; 
/*[15:46:08][8 ms]*/ UPDATE `ejj_shop` SET `county_id` = '0' WHERE `id` = '213'; 
/*[15:46:10][2 ms]*/ UPDATE `ejj_shop` SET `county_id` = '0' WHERE `id` = '215'; 
/*[15:46:13][4 ms]*/ UPDATE `ejj_shop` SET `county_id` = '0' WHERE `id` = '216'; 