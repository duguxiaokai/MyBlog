<?php return array(
'id'=>"sys_file",
'title'=>"文件",
'tablename'=>"sys_file",
'filter'=>"SYS_ORGID=orgid()",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"主键",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"文件名称",'fieldtype'=>"string",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"filepath",'name'=>"文件路径",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"owner_id",'name'=>"隶属记录ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"field",'name'=>"字段ID",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"version",'name'=>"版本号",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"modelid",'name'=>"模型id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"notes",'name'=>"说明",'fieldtype'=>"string",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"上传者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"上传日期",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>