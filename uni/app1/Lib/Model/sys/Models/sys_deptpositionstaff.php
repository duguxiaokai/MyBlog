<?php return array(
'id'=>"sys_deptpositionstaff",
'title'=>"sys_部门岗位员工关系表",
'tablename'=>"sys_deptpositionstaff",
'actions'=>"add,edit,search",
'fields'=>array(
 array('id'=>"id",'name'=>"id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid()",'forsearch'=>"true",),
 array('id'=>"deptid",'name'=>"部门",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept  where SYS_ORGID=orgid()",),
 array('id'=>"positionid",'name'=>"岗位",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_position  where SYS_ORGID=orgid()",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",),
),

) ?>