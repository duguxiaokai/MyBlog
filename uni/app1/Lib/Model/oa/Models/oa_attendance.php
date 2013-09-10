<?php return array(
'id'=>"oa_attendance",
'title'=>"签到",
'tablename'=>"oa_attendance",
'actions'=>"add,del",
'fields'=>array(
 array('id'=>"id",'name'=>"字段1",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"markdate",'name'=>"日期",'defaultdata'=>"today()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"begintime",'name'=>"上班时间",'fieldtype'=>"datetime",'type'=>"datetime",'defaultdata'=>"now()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"plan",'name'=>"心情",'fieldtype'=>"string",'type'=>"textarea",'width'=>"96%",'height'=>"45px",),
 array('id'=>"workhour",'name'=>"工时",'fieldtype'=>"real",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffid",'name'=>"员工id",'fieldtype'=>"int",'defaultdata'=>"Me()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffname",'name'=>"员工姓名",'fieldtype'=>"string",'defaultdata'=>"MyStaffName()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"string",'type'=>"SYS_ORGID",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>