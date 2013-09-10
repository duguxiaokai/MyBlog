<?php return array(
'id'=>"oa_member_projectlist",
'title'=>"员工列表",
'tablename'=>"sys_staff",
'openurl'=>"Oa/Project/viewstaff/modelid/oa_member_projectlist",
'filter'=>"id in (select staffid from oa_project_staff) and SYS_ORGID = orgid()",
'open_window_style'=>"popup:middle",
'pagerows'=>"10",
'actions'=>"open,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'defaultdata'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"员工姓名",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"mobile",'name'=>"手机",'forsearch'=>"true",'formate'=>"phone",),
 array('id'=>"email",'name'=>"Email",'fieldtype'=>"string",'unique'=>"true",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"deptid",'name'=>"部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept where SYS_ORGID=orgid()",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",),
),

) ?>