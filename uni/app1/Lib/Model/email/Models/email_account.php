<?php return array(
'id'=>"email_account",
'title'=>"邮件账号表",
'tablename'=>"email_account",
'actions'=>"tableedit,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"email",'name'=>"邮箱地址",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"username",'name'=>"用户名",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"password",'name'=>"邮箱密码",'fieldtype'=>"string",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'readonly'=>"true",),
),

) ?>