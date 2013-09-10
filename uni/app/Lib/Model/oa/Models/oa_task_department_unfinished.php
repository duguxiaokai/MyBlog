<?php return array(
'id'=>"oa_task_department_unfinished",
'title'=>"本部门未完成工作列表",
'tablename'=>"oa_task",
'sql'=>"select id,name,cat,begindate,enddate,executerid,finishper,SYS_ADDUSER,SYS_ORGID from oa_task where executerid in(select id from sys_staff where deptid=(select deptid from sys_staff where username=loginuser() and SYS_ORGID=orgid())) and finishper <>1",
'orderby'=>"executerid desc",
'groupby'=>"y",
'actions'=>"open,edit,filter2,page,groupby",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",'fieldtype'=>"string",'type'=>"textarea",),
 array('id'=>"cat",'name'=>"工作类别",'fieldtype'=>"string",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"executerid",'name'=>"执行人",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",),
 array('id'=>"finishper",'name'=>"完成状态",'fieldtype'=>"string",'type'=>"radio",'data'=>"0:进行中,1:已完成",'defaultdata'=>"0",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>