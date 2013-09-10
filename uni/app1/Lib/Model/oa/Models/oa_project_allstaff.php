<?php return array(
'id'=>"oa_project_allstaff",
'title'=>"人员列表",
'tablename'=>"sys_staff",
'filter'=>"SYS_ORGID = orgid()",
'pagerows'=>"10",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"name",'name'=>"姓名",'forsearch'=>"true",),
 array('id'=>"deptid",'name'=>"部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept",),
),

) ?>