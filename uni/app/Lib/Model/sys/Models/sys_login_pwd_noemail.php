<?php return array(
'id'=>"sys_login_pwd_noemail",
'title'=>"修改登录密码",
'fields'=>array(
 array('id'=>"name",'name'=>"用户名",'readonly'=>"true",),
 array('id'=>"login_pwd",'name'=>"原密码",'fieldtype'=>"string",'type'=>"password",'nullable'=>"false",),
 array('id'=>"newlogin_pwd",'name'=>"新密码",'fieldtype'=>"string",'type'=>"password",'nullable'=>"false",),
 array('id'=>"newlogin_pwd2",'name'=>"重复密码",'type'=>"password",'nullable'=>"false",),
),

) ?>