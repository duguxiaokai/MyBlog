<?php return array(
'id'=>"sys_staff",
'title'=>"员工列表",
'tablename'=>"sys_staff",
'filter'=>"SYS_ORGID=orgid()",
'open_window_style'=>"popup:small",
'pagerows'=>"10",
'actions'=>"add,del,edit,tableedit,search,page",
'cols'=>"2",
'sql_after_deleted'=>"delete from sys_deptpositionstaff where staffid=recordid()",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"员工姓名",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"gender",'name'=>"性别",'type'=>"radio",'data'=>"M:男,F:女",'forsearch'=>"true",),
 array('id'=>"mobile",'name'=>"手机",'forsearch'=>"true",),
 array('id'=>"email",'name'=>"Email",'fieldtype'=>"string",'unique'=>"true",'formate'=>"email",),
 array('id'=>"deptid",'name'=>"部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept where SYS_ORGID = orgid()",),
 array('id'=>"birthday",'name'=>"出生日期",'fieldtype'=>"date",'type'=>"date",'isvisible'=>"false",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
 array('id'=>"statue",'name'=>"在职状态",'fieldtype'=>"string",'length'=>"1",'type'=>"radio",'data'=>"1:在职,0:离职",),
 array('id'=>"username",'name'=>"用户名",'type'=>"popupselectone",'data'=>"model:sys_selectuser",'forsearch'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'visibleWhenAdd'=>"false",),
),

) ?>