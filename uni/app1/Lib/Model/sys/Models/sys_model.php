<?php return array(
'id'=>"sys_model",
'title'=>"模型表",
'tablename'=>"sys_model",
'actions'=>"add,del,edit",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'nullable'=>"true",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"modelid",'name'=>"模型ID",'fieldtype'=>"string",'length'=>"50",),
 array('id'=>"modelname",'name'=>"模型名称",'fieldtype'=>"string",'length'=>"50",),
 array('id'=>"tags",'name'=>"标记",'fieldtype'=>"string",'length'=>"50",'type'=>"checkboxlist",'data'=>"1:网站模型",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>