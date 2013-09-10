<?php return array(
'id'=>"sys_dept_mcss",
'title'=>"部门列表",
'tablename'=>"sys_dept",
'open_window_style'=>"popup:small",
'filter'=>"SYS_ORGID = 17",
'pagerows'=>"10",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'defaultdata'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"部门名称",'forsearch'=>"true",),
 array('id'=>"parentid",'name'=>"上级部门",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept where SYS_ORGID=orgid()",),
 array('id'=>"notes",'name'=>"备注",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>