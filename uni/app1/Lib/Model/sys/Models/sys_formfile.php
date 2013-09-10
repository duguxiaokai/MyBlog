<?php return array(
'id'=>"sys_formfile",
'title'=>"附件列表",
'tablename'=>"sys_formfile",
'pagerows'=>"20",
'actions'=>"add,del,edit",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"ownerid",'name'=>"所属记录ID",'fieldtype'=>"string",'length'=>"32",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"文件名",'fieldtype'=>"string",'length'=>"100",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"filepath",'name'=>"文件",'fieldtype'=>"string",'length'=>"256",'type'=>"file",),
 array('id'=>"notes",'name'=>"说明",'fieldtype'=>"string",'length'=>"50",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>