　　系统简介

　　BOSS（Business & Operation Support System，BOSS）指的是业务运营支撑系统。
 
　　BOSS分为四个部分：计费及结算系统、营业与账务系统、客户服务系统和决策支持系统。
 
　　BOSS从业务层面来看就是一个框架，来承载业务系统、CRM系统、计费系统。实现统一框架中的纵向、横向管理。
 
　　本系统整合了通用后台、小家政后台、企业保洁后台、运营CMS后台、订单后台、数据后台等系统，实现了业务的统一入口、统一操作体验、统一业务逻辑、统一业务管理...并对用户界面进行了优化，简化操作，提升工作效率；<p/>

　　相关项目资料：<br/>
　　1. 产品需求 ： http://i99kyj.axshare.com<br/>
　　2. 系统设计 ： http://i99kyj.axshare.com<br/>
　　3. 测试计划 ： http://i99kyj.axshare.com<br/>
　　4. 运维手册 ： http://i99kyj.axshare.com<br/>

　　BOSS系统代码框架结构说明：<br/><br/>

　　-- common （通用模块，自动生成数据库CRUD类，封装常用工具类）<br/>
　　-- core （核心模块，继承并扩展common）<br/>
　　-- api （接口，调用并扩展core，也可以直接扩展common）<br/>
　　-- boss （BOSS后台系统）<br/>
　　-- console （控制台框架模板）<br/>
　　-- datasql （数据库脚本）<br/>
　　-- environments （环境配置）<br/>
　　-- frontend （前台框架模板）<br/>
　　-- tests （测试框架）<br/>
　　-- vendor （YII 2.0框架库）<br/><br/>

　　init （初始化脚本）<br/>
　　init.bat （初始化启动命令）<br/><br/>

　　composer.json （COMPOSER资源文件）<br/>
　　composer.lock （操作锁）<br/>
　　composer.phar （PHP执行命令）<br/>
　　requirements.php （环境检验）<br/>
　　LICENSE.md  （授权协议）<br/>
　　README.md   （本说明）<br/>