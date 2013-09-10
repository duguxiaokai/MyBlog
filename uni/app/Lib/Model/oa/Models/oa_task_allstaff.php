<?php return array(
'id'=>"oa_task_allstaff",
'title'=>"所有员工工作列表查看",
'notes'=>"SYS_ORGID=orgid()",
'tablename'=>"oa_task",
'sql'=>"SELECT a.id, a.name,a.worktime,b.name AS executername, a.begindate, a.enddate, a.cat, a.finishper, a.SYS_ADDUSER 
FROM oa_task a 
LEFT JOIN sys_staff b ON b.id = a.executerid 
WHERE begindate >= ( 
SELECT subdate( curdate( ) , date_format( curdate( ) , '%w' ) -1 ) ) 
AND begindate <= ( 
SELECT subdate( curdate( ) , date_format( curdate( ) , '%w' ) -7 ) ) 
AND enddate >= (SELECT subdate( curdate( ) , date_format( curdate( ) , '%w' ) -1 ) ) AND enddate <= (SELECT subdate( curdate( ) , date_format( curdate( ) , '%w' ) -7 )) ",
'orderby'=>"executername",
'groupby'=>"y",
'actions'=>"edit,del,search,search2,filter,page,groupby",
'fields'=>array(
array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
array('id'=>"name",'name'=>"任务名称",'fieldtype'=>"string",'type'=>"textarea",'forsearch'=>"true",'nullable'=>"false",),
array('id'=>"worktime",'name'=>"工时",),
array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"now()",'forsearch'=>"true",),
array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"now()",),
array('id'=>"executername",'name'=>"执行人",),
array('id'=>"finishper",'name'=>"完成状态",'type'=>"radio",'data'=>"0:进行中,1:已完成",'defaultdata'=>"0",),
array('id'=>"SYS_ADDUSER",'name'=>"创建者",'visibleWhenAdd'=>"false",),
array('id'=>"cat",'name'=>"报告类别",'fieldtype'=>"string",'type'=>"dropdown",'data'=>",日报,周报,月报",),
array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'visibleWhenAdd'=>"false",),
),

) ?>