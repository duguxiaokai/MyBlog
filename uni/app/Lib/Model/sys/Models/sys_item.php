<?php return array(
'id'=>"sys_item",
'title'=>"选项表",
'tablename'=>"sys_item",
'filter'=>"SYS_ORGID = orgid()",
'actions'=>"add,del,edit,tableedit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",'fieldtype'=>"string",),
 array('id'=>"orderlist",'name'=>"顺序",'fieldtype'=>"int",),
 array('id'=>"type",'name'=>"归类",'fieldtype'=>"string",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>