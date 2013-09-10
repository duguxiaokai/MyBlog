<?php return array(
'id'=>"sys_userinfo",
'title'=>"账号信息",
'tablename'=>"sys_user",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"用户名",'readonly'=>"true",),
 array('id'=>"email",'name'=>"Email",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'readonly'=>"true",),
),

) ?>