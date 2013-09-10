<?php return array(
'id'=>"sys_user_saas",
'title'=>"用户表",
'tablename'=>"sys_user",
'filter'=>"orgids like '%orgid()%'",
'open_window_style'=>"popup:small",
'actions'=>"add,edit,del,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"用户名",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"email",'name'=>"Email",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"psw",'name'=>"密码",'fieldtype'=>"string",'type'=>"password",'isvisible'=>"false",),
 array('id'=>"roleid",'name'=>"角色",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role where SYS_ORGID=orgid()",'forsearch'=>"true",),
 array('id'=>"orgids",'name'=>"组织ID",'fieldtype'=>"string",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"nickname",'name'=>"昵称",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"email_pwd",'name'=>"邮箱登录密码",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>