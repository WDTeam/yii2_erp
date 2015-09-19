# 系统简介

　　BOSS（Business & Operation Support System，BOSS）指的是业务运营支撑系统。

　　BOSS分为四个部分：计费及结算系统、营业与账务系统、客户服务系统和决策支持系统。

　　BOSS从业务层面来看就是一个框架，来承载业务系统、CRM系统、计费系统。实现统一框架中的纵向、横向管理。

　　本系统整合了通用后台、小家政后台、企业保洁后台、运营CMS后台、订单后台、数据后台等系统，实现了业务的统一入口、统一操作体验、统一业务逻辑、统一业务管理...并对用户界面进行了优化，简化操作，提升工作效率；

　　相关项目资料：<br/>
　　1. 产品需求 ： http://i99kyj.axshare.com<br/>
　　2. 系统设计 ： http://i99kyj.axshare.com<br/>
　　3. 测试计划 ： http://i99kyj.axshare.com<br/>
　　4. 运维手册 ： http://i99kyj.axshare.com<br/>

# BOSS系统代码框架结构说明：<br/><br/>

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

## 注意事项
  
   1. 在任何人参与项目之前，请记住，一定不能对environments 配置目录下得文件修改，
      
      *不要修改enviroments目录*

   2. 然后，自己本机上搭建数据库local_boss_db,  username: local_boss_db_dbo; password:localboss，然后执行 ./yii migration就可以在本机上进行开发了。
 
 
## 部署说明：

```
	1、代码目录 /code/ejj-enterprise-boss
	2、部署规则
	   域名:端口 boss.me:80			root /code/ejj-enterprise-boss/boss/web
	   域名:端口 api.me:80				root /code/ejj-enterprise-boss/api/web
	3、添加rewrite
	   if (!-e $request_filename){
			rewrite "^(.*)$" /index.php?r=$1 last;
		}
	4、dev、test环境分别在域名前面添加dev.和test.
	5、Nginx部署模型仅供参考
		server {
	        listen       80;
	        server_name  boss.1jiajie.com;
			send_timeout 0;
	        location / {
	            root   /code/ejj-enterprise-boss/boss/web;
	            index  index.html index.htm index.php;
				autoindex on;
				if (!-e $request_filename){
					rewrite "^(.*)$" /index.php?r=$1 last;
				}
	        }

	        location ~ \.php$ {
				root	/code/ejj-enterprise-boss/boss/web;
				fastcgi_pass	127.0.0.1:9000;
				fastcgi_index	index.php;
				fastcgi_param	SCRIPT_FILENAME  $document_root/$fastcgi_script_name;
				fastcgi_buffers 4 128k;
				include			fastcgi_params;
			}
	    }
	    server {
	        listen       80;
	        server_name  api.1jiajie.com;
			send_timeout 0;
	        location / {
	            root   /code/ejj-enterprise-boss/api/web;
	            index  index.html index.htm index.php;
				autoindex on;
				if (!-e $request_filename){
					rewrite "^(.*)$" /index.php?r=$1 last;
				}
	        }

	        location ~ \.php$ {
				root	/code/ejj-enterprise-boss/api/web;
				fastcgi_pass	127.0.0.1:9000;
				fastcgi_index	index.php;
				fastcgi_param	SCRIPT_FILENAME  $document_root/$fastcgi_script_name;
				fastcgi_buffers 4 128k;
				include			fastcgi_params;
			}
	    }
	   6、Apache部署模型
		<VirtualHost *:80>
		    DocumentRoot "/code/ejj-enterprise-boss/boss/web"
		    ServerName boss.1jiajie.com
		    ServerAlias 
		  <Directory "/code/ejj-enterprise-boss/boss/web">
		      Options FollowSymLinks ExecCGI
		      AllowOverride All
		      Order allow,deny
		      Allow from all
		      Require all granted
		  </Directory>
		</VirtualHost>

		<VirtualHost *:80>
		    DocumentRoot "/code/ejj-enterprise-boss/api/web"
		    ServerName api.1jiajie.com
		    ServerAlias 
		  <Directory "/code/ejj-enterprise-boss/api/web">
		      Options FollowSymLinks ExecCGI
		      AllowOverride All
		      Order allow,deny
		      Allow from all
		      Require all granted
		  </Directory>
		</VirtualHost>

		在对应的根目录下面增加Rewrite解析的.htaccess
		代码
		RewriteEngine on

		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule . index.php


```
