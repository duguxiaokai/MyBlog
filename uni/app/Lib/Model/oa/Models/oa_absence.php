<?php return array(
'id'=>"oa_absence",
'title'=>"签到",
'tablename'=>"oa_attendance",
'actions'=>"add,del,edit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"字段1",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"markdate",'name'=>"日期",'defaultdata'=>"today()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"begintime",'name'=>"上班时间",'type'=>"datetime",'visibleWhenAdd'=>"false",),
 array('id'=>"endtime",'name'=>"下班时间",'type'=>"datetime",'defaultdata'=>"now()",'visibleWhenAdd'=>"false",),
 array('id'=>"plan",'name'=>"工作计划",'fieldtype'=>"string",'visibleWhenAdd'=>"false",),
 array('id'=>"summary",'name'=>" 总结：",'type'=>"textarea",'width'=>"96%",'height'=>"55px",),
 array('id'=>"workhour",'name'=>"工时",'fieldtype'=>"real",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffid",'name'=>"员工id",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",'defaultdata'=>"Me()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"staffname",'name'=>"员工姓名",'fieldtype'=>"string",'defaultdata'=>"MyStaffName()",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"string",'type'=>"SYS_ORGID",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>