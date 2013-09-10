<?php return array(
'id'=>"sys_login",
'title'=>"登录",
'sql'=>"select '' as username,'' as password",
'actions'=>"add",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"username",'name'=>"用户名",),
 array('id'=>"password",'name'=>"密码",'type'=>"password",),
),

) ?>