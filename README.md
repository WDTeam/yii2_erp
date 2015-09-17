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

部署说明：
-------------------

```
	1、代码目录 /code/ejj-enterprise-boss
	2、部署规则
	   域名:端口 boss.1jiajie.com:80			root /code/ejj-enterprise-boss/boss/web
	   域名:端口 api.1jiajie.com:80				root /code/ejj-enterprise-boss/api/web
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
	   

```