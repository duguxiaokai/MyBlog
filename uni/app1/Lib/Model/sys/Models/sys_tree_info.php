<?php return array(
'id'=>"sys_tree_info",
'title'=>"信息目录",
'tablename'=>"sys_tree",
'filter'=>"type='info'",
'actions'=>"add,del,edit,search,page",
'keyfield'=>"id",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"parentid",'name'=>"上级目录",'data'=>"sql:select id,name from sys_tree where type='info'",'readonly'=>"true",),
 array('id'=>"name",'name'=>"目录名称",),
 array('id'=>"type",'name'=>"类别",'defaultdata'=>"info",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"SYS_ADDTIME",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"SYS_ADDTIME",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"SYS_ADDUSER",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"SYS_EDITTIME",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"SYS_EDITUSER",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>