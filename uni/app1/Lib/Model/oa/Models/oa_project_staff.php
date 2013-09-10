<?php return array(
'id'=>"oa_project_staff",
'title'=>"项目人员",
'tablename'=>"oa_project_staff",
'sql'=>"select a.id,a.projectid,a.staffid,a.roleid,a.note,b.name as staffname from oa_project_staff a left join sys_staff b on b.id=a.staffid

",
'openurl'=>"default",
'editurl'=>"oa/Project/staff/pagetype/edit",
'addurl'=>"oa/Project/staff/pagetype/add",
'open_window_style'=>"popup:large",
'pagerows'=>"10",
'actions'=>"add,del,edit",
'fields'=>array(
 array('id'=>"id",'name'=>"id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"projectid",'name'=>"项目",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from ext_pm_project",'isvisible'=>"false",),
 array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"int",'isvisible'=>"false",),
 array('id'=>"staffname",'name'=>"员工名称",'fieldtype'=>"string",),
 array('id'=>"roleid",'name'=>"角色",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role",),
 array('id'=>"note",'name'=>"备注",),
),

) ?>