<?php return array(
'id'=>"oa_project_staff_add",
'title'=>"项目人员",
'tablename'=>"oa_project_staff",
'actions'=>"add,del,open,edit",
'fields'=>array(
 array('id'=>"id",'name'=>"id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"projectname",'name'=>"项目名称",'virtualfield'=>"true",),
 array('id'=>"projectid",'name'=>"项目",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffname",'name'=>"员工名称",'fieldtype'=>"string",'type'=>"popupselectone",'data'=>"model:oa_staff(name:staffname,id:staffid)",'readonly'=>"true",'nullable'=>"false",'width'=>"160px",),
 array('id'=>"roleid",'name'=>"角色",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role where SYS_ORGID=orgid()",),
 array('id'=>"note",'name'=>"备注",),
),

) ?>