<?php return array(
'id'=>"sys_selectuser",
'title'=>"选择登录帐号",
'base'=>"sys_user",
'filter'=>" name not in(select username from sys_staff WHERE SYS_ORGID=orgid() and  username<>'') and orgids like '%orgid()%' ",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"用户名",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"email",'name'=>"Email",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"roleid",'name'=>"角色",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role",'forsearch'=>"true",),
),

) ?>