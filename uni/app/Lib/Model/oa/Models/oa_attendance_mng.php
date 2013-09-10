<?php return array(
'id'=>"oa_attendance_mng",
'title'=>"日常考勤打卡记录",
'tablename'=>"oa_attendance",
'filter'=>"SYS_ORGID=orgid()",
'pagerows'=>"5",
'actions'=>"search,page",
'fields'=>array(
array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",),
array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid()",'isvisible'=>"false",'forsearch'=>"true",'width'=>"10%",),
array('id'=>"staffname",'name'=>"员工姓名",'fieldtype'=>"string",),
array('id'=>"begintime",'name'=>"上班时间",'fieldtype'=>"datetime",'type'=>"datetime",'style'=>"width:150px",'width'=>"15%",),
array('id'=>"plan",'name'=>"上班心情",'fieldtype'=>"string",'forsearch'=>"true",'width'=>"30%",),
array('id'=>"endtime",'name'=>"下班时间",'fieldtype'=>"datetime",'type'=>"datetime",'width'=>"15%",),
array('id'=>"summary",'name'=>"下班总结",'fieldtype'=>"string",'forsearch'=>"true",'width'=>"30%",),
array('id'=>"workhour",'name'=>"工时",'fieldtype'=>"real",'isvisible'=>"false",),
array('id'=>"markdate",'name'=>"日期",'fieldtype'=>"date",'type'=>"date",'isvisible'=>"false",),
array('id'=>"SYS_ORGID",'name'=>"组织ID",'isvisible'=>"false",'readonly'=>"true",),
),




) ?>