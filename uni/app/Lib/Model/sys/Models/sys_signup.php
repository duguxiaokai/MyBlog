<?php return array(
'id'=>"sys_signup",
'title'=>"注册",
'tablename'=>"sys_user",
'filter'=>"name=''",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"email",'name'=>"邮箱",'unique'=>"true",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"name",'name'=>"用户名",'unique'=>"true",'nullable'=>"false",),
 array('id'=>"psw",'name'=>"密码",'type'=>"password",),
),

) ?>