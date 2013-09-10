<?php return array(
'id'=>"sys_user_mcss",
'title'=>"用户表",
'tablename'=>"sys_user",
'open_window_style'=>"popup:small",
'actions'=>"add,del,edit,search,search2,filter,page,export,import",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"用户名",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"psw",'name'=>"密码",'fieldtype'=>"string",'type'=>"password",'isvisible'=>"false",'nullable'=>"false",),
 array('id'=>"repeatpsw",'name'=>"重复密码",'fieldtype'=>"string",'type'=>"hidden",'virtualfield'=>"true",'nullable'=>"false",),
 array('id'=>"login_option",'name'=>"登录类型",'type'=>"dropdown",'data'=>":帐号和密码,P:密码",),
),

) ?>