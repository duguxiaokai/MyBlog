<?php return array(
'id'=>"sys_position_mcss",
'title'=>"岗位列表",
'tablename'=>"sys_position",
'filter'=>"SYS_ORGID = orgid()",
'actions'=>"add,del,edit,search,page,export,import",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"岗位名称",'fieldtype'=>"string",),
 array('id'=>"deptid",'name'=>"所属部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept where SYS_ORGID=orgid()",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",),
 array('id'=>"isleader",'name'=>"是否领导岗位",'type'=>"dropdown",'data'=>"0:,1:是",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>