<?php return array(
'id'=>"list_user",
'title'=>"账号管理",
'tablename'=>"sys_user",
'groupby'=>"n",
'open_window_style'=>"popup:small",
'pagerows'=>"50",
'actions'=>"add,del,edit,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'defaultdata'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"name",'name'=>"用户名",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"psw",'name'=>"密码",'type'=>"hidden",'isvisible'=>"false",'visibleWhenAdd'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"email",'name'=>"注册邮箱",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"roleid",'name'=>"角色",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role",'forsearch'=>"true",),
)
) ?>