<?php
   
return $sys_config=array(
	//数据库设置移到 app_config.php文件中了
	'DB_TYPE'=>'MYSQL',                  
	'DB_PREFIX'=>'', 
	'DB_HOST'=>'localhost',
	//'DB_HOST'=>'192.168.1.3',
 	'DB_NAME'=>'uni',
	'DB_USER'=>'root',
	'DB_PWD' =>'',
	'DB_PORT'=>3306,
	'mcss_app'=>'smesforce',
	'DEFAULT_GROUP'=>'oa',//这是打开诸如http://localhost/jusaas/index.php时进入的默认模块，必须是'APP_GROUP_LIST'中的一个
	'mcss_theme'=>'default',//blue,bronze,yellow,yellow1,default,apple,fco


	'APP_DEBUG'=>false,
	'APP_AUTOLOAD_PATH'=> 'Think.Util.,ORG.Util.,@.MCSS.',
	'APP_GROUP_LIST'=>'Sys,List,Home,Oa,Mcss,Admin,Test,Util,System',//这是模 块列表，对应Lib\Action下的目录，也对应Tpl\default下的目录

	'mcss_lang'=>'en',//语言。支持cn:中文、en:英文	
	'mcss_saas'=>false,//是否支持SaaS，即多组织
	'mcss_needlogin_models'=>"List123123",//需要登录才能访问的模块，多个之间用逗号","分开。这对继承CommonAction的模块无效
	'mcss_everyone_canuse_modelmanager'=>true,//任何角色都可以执行对modelmanager的操作
	'mcss_max_filesize'=>2,//文件上传最大MB	
	'mcss_loadmodel_everytime'=>true,//是否每次都重新加载模型文件。建议稳定后采用false
	'mcss_adminuserallaccess'=>true,//true表示用户名为"admin"的用户拥有所有模型权限
	'mcss_checkaccess_everytime'=>true,//每次重新加载角色权限文件进行检查，不用重新登录。
	'mcss_checkmenu_everytime'=>true,//每次重新加载后台管理菜单权限
	'mcss_access_base'=>'ROLE', //后台菜单权限给予角色还是用户。可选：ROLE_USER：角色+用户,USER：用户,ROLE：角色
	'mcss_user_default_role'=>'user',//新注册用户默认角色
	//'mcss_access_disable'=>true,//是否禁用权限控制。true表示不用对模型进行权限检查，所有模型公开
	
	//下列选项可以在程序调试阶段建议设为true，若为提高性能，则应设为false。
	//系统日志网址是：http://124.42.127.137/index.php/List/List/list2/param:table/sys_log/
	//SQL和错误日志网址是：
	'mcss_log'=>true,//是否启用日志
	'mcss_log_sql'=>true,//是否记录sql语句到TP日志中
	'mcss_log_getdata'=>true,//是否记录取数据的sql
	'mcss_log_openmodel'=>true,//是否记录打开模型
	'mcss_log_getonerecord'=>true,//是否记录获取一条数据的sql
	'mcss_log_saveRecord'=>true,//是否记录保存数据的sql
	'mcss_log_anyonecansee'=>true,//任何人都能看当天ThinkPHP日志，即调用方法index.php/Admin/Index/log
	//系统管理邮件设置，用来发送系统邮件，如发送取回密码邮件

	'adminemail_username'=>'chenkunji',
	'adminemail_password'=>'wwwwwww2',
	'adminemail_fromemail'=>'chenkunji@qq.com',
	'adminemail_smtp'=>'smtp.qq.com',//SMTP设置
	'adminemail_port'=>'25',//例如，25是qq邮箱的端口号
	
	
	'mcss_needverifycode'=>false,//检查登录账户和密码是是否需要检查验证码
	//'mcss_needorgs'=>false,//登录页面是否显示多帐号支持

);
?>