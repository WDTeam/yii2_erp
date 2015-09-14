BOSS系统框架代码：

-- common （通用模块，自动生成数据库CRUD类，封装常用工具类）
-- core （核心模块，继承并扩展common）
-- api （接口，调用并扩展core，也可以直接扩展common）
-- boss （BOSS后台系统）
-- console （控制台框架模板）
-- datasql （数据库脚本）
-- environments （环境配置）
-- frontend （前台框架模板）
-- tests （测试框架）
-- vendor （YII 2.0框架库）

init （初始化脚本）
init.bat （初始化启动命令）

composer.json （COMPOSER资源文件）
composer.lock （操作锁）
composer.phar （PHP执行命令）
requirements.php （环境检验）
LICENSE.md  （授权协议）
README.md   （本说明）
