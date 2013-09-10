<?php return array(
'id'=>"oa_filedir",
'title'=>"文件目录",
'tablename'=>"oa_filedir",
'actions'=>"add,del,edit,search,page",
'keyfield'=>"id",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"parentid",'name'=>"上级目录",'type'=>"dropdown",'data'=>"sql:select id,name from oa_filedir where SYS_ORGID = orgid()",),
 array('id'=>"name",'name'=>"目录名称",),
 array('id'=>"SYS_ORGID",'name'=>"SYS_ADDTIME",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"SYS_ADDTIME",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"SYS_ADDUSER",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"SYS_EDITTIME",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"SYS_EDITUSER",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>