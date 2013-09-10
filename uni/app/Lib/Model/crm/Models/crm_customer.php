<?php return array(
'id'=>"crm_customer",
'title'=>"客户列表",
'tablename'=>"crm_customer",
'open_window_style'=>"samewindow",
'actions'=>"add,del,open,edit,search,search2,import,export,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"客户名称",'fieldtype'=>"string",'length'=>"20",'forsearch'=>"true",),
 array('id'=>"domain",'name'=>"邮箱后缀",'fieldtype'=>"string",'length'=>"50",'visibleWhenAdd'=>"false",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'length'=>"500",'type'=>"textarea",'visibleWhenAdd'=>"false",),
 array('id'=>"mail_emotion",'name'=>"邮件情绪值",'fieldtype'=>"float",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"datetime",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>