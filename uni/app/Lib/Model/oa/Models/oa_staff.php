<?php return array(
'id'=>"oa_staff",
'title'=>"员工列表",
'tablename'=>"sys_staff",
'filter'=>"SYS_ORGID = orgid()",
'open_window_style'=>"popup:small",
'pagerows'=>"5",
'actions'=>"add,del,edit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'defaultdata'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"员工姓名",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"gender",'name'=>"性别",'type'=>"radio",'data'=>"F:女,M:男",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"mobile",'name'=>"手机",'isvisible'=>"true",'forsearch'=>"true",'formate'=>"phone",),
 array('id'=>"username",'name'=>"用户名",'type'=>"popupselectone",'data'=>"model:sys_selectuser",'isvisible'=>"false",'readonly'=>"true",'unique'=>"true",'forsearch'=>"true",),
 array('id'=>"email",'name'=>"Email",'fieldtype'=>"string",'isvisible'=>"false",'unique'=>"true",'nullable'=>"false",'formate'=>"email",),
 array('id'=>"deptid",'name'=>"部门",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_dept",'isvisible'=>"false",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",),
),

) ?>