<?php return array(
'id'=>"oa_task_staff_stat",
'title'=>"所有员工本周工作统计",
'sql'=>"select * from (SELECT a.id,b.name as executername,count(a.id) as week_worknumber,executerid
		FROM oa_task a
		LEFT JOIN sys_staff b ON b.id = a.executerid
		WHERE YEARWEEK(date_format(begindate ,'%Y-%m-%d')) = YEARWEEK(curdate())
                AND YEARWEEK(date_format(enddate ,'%Y-%m-%d')) = YEARWEEK(curdate())
		AND a.cat = '周报' group by b.name)AS w
		LEFT JOIN (
		SELECT count(a.id) as day_worknumber,executerid
		FROM oa_task a
		LEFT JOIN sys_staff b ON b.id = a.executerid
		WHERE YEARWEEK(date_format(begindate ,'%Y-%m-%d')) = YEARWEEK(curdate())
                AND YEARWEEK(date_format(enddate ,'%Y-%m-%d')) = YEARWEEK(curdate())
		AND a.cat = '日报' group by b.name
		)d ON w.executerid = d.executerid
		LEFT JOIN (
		SELECT count(a.id) as day_finishper,executerid
		FROM oa_task a
		LEFT JOIN sys_staff b ON b.id = a.executerid
		WHERE YEARWEEK(date_format(begindate ,'%Y-%m-%d')) = YEARWEEK(curdate())
                AND YEARWEEK(date_format(enddate ,'%Y-%m-%d')) = YEARWEEK(curdate())
		AND a.cat = '日报' AND a.finishper =1 group by b.name
		)e ON e.executerid = d.executerid
		LEFT JOIN (
		SELECT count(a.id) as week_finishper,executerid
		FROM oa_task a
		LEFT JOIN sys_staff b ON b.id = a.executerid
		WHERE YEARWEEK(date_format(begindate ,'%Y-%m-%d')) = YEARWEEK(curdate())
                AND YEARWEEK(date_format(enddate ,'%Y-%m-%d')) = YEARWEEK(curdate())
		AND a.cat = '周报' AND a.finishper =1
and a.SYS_ORGID=orgid()
 group by b.name
		)f ON f.executerid = w.executerid",
'filter'=>"SYS_ORGID=orgid()",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"executername",'name'=>"员工姓名",'type'=>"dropdown",),
 array('id'=>"week_worknumber",'name'=>"周工作数量",'type'=>"dropdown",),
 array('id'=>"week_finishper",'name'=>"周工作完成率",'type'=>"dropdown",),
 array('id'=>"day_worknumber",'name'=>"日工作数量",'type'=>"dropdown",),
 array('id'=>"day_finishper",'name'=>"日工作完成率",'type'=>"dropdown",),
),

) ?>