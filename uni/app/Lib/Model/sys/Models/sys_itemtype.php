<?php return array(
'id'=>"sys_itemtype",
'title'=>"选项类别",
'tablename'=>"sys_itemtype",
'actions'=>"add,del,edit,page",
'keyfield'=>"id",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"选项类别代码",'fieldtype'=>"string",),
 array('id'=>"name",'name'=>"选项类别名称",'fieldtype'=>"string",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>