<?php return array(
'id'=>"sys_role",
'title'=>"角色表",
'tablename'=>"sys_role",
'filter'=>"SYS_ORGID = orgid()",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'defaultdata'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"编码",'isvisible'=>"false",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"name",'name'=>"角色名称",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"notes",'name'=>"角色描述",'type'=>"textarea",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>