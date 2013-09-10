<?php return array(
'id'=>"oa_project_task_info",
'title'=>"任务项目成本信息",
'sql'=>"SELECT id,name,`executerid`, (select code from oa_project b where a.projectid=b.id) as prcode,(select name from oa_project c where a.projectid=c.id) as prname ,price,worktime,totalprice,amount,unitname,enddate,status FROM `oa_task` a where SYS_ORGID=orgid() and status='checked'",
'orderby'=>" enddate desc",
'actions'=>"search,filter",
'fields'=>array(
 array('id'=>"id",'name'=>"任务ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"任务名称",'forsearch'=>"true",),
 array('id'=>"executerid",'name'=>"执行者",'type'=>"smartselect",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid()",'forsearch'=>"true",),
 array('id'=>"prcode",'name'=>"项目号",'forsearch'=>"true",),
 array('id'=>"prname",'name'=>"项目名称",'forsearch'=>"true",),
 array('id'=>"amount",'name'=>"任务量",),
 array('id'=>"unitname",'name'=>"任务量单位",),
 array('id'=>"price",'name'=>"单位成本",),
 array('id'=>"worktime",'name'=>"工时",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"totalprice",'name'=>"总价",),
 array('id'=>"enddate",'name'=>"结束日期",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>