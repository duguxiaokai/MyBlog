<?php return array(
'id'=>"sys_select_position",
'title'=>"选择岗位列表",
'base'=>"sys_position",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"岗位名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"deptid",'name'=>"所属部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept where SYS_ORGID=orgid()",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"isleader",'name'=>"是否领导岗位",'type'=>"dropdown",'data'=>"0:,1:是",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>