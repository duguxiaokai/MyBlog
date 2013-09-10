<?php return array(
'id'=>"sys_login_pwd",
'title'=>"修改登录密码",
'fields'=>array(
 array('id'=>"name",'name'=>"用户名",'readonly'=>"true",),
 array('id'=>"login_pwd",'name'=>"原密码",'fieldtype'=>"string",'type'=>"password",),
 array('id'=>"newlogin_pwd",'name'=>"新密码",'fieldtype'=>"string",'type'=>"password",),
 array('id'=>"newlogin_pwd2",'name'=>"重复密码",'type'=>"password",),
 array('id'=>"email_pwd",'name'=>"邮箱密码",'type'=>"password",),
),

) ?>