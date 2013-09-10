<?php return array(
'id'=>"oa_task_projectweek",
'title'=>"项目工作周安排",
'tablename'=>"oa_task",
'sql'=>"select a.*,c.name as projectname from oa_task a left join oa_project c on c.id=a.projectid ",
'filter'=>"SYS_ORGID=orgid()",
'actions'=>"add,del,edit,tableedit,search,page,groupby",
'wayofcopyfields'=>"parent_and_child",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"diy",'name'=>"周次",'fieldtype'=>"string",'length'=>"50",'type'=>"popupselectone",'data'=>"model:oa_pm_period(name:diy)",'forsearch'=>"true",),
 array('id'=>"projectid",'name'=>"项目",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"projectname",'name'=>"项目名称",'type'=>"popupselectone",'data'=>"model:oa_project(id:projectid,name:projectname)",'forsearch'=>"true",'virtualfield'=>"true",),
 array('id'=>"name",'name'=>"任务名称",'forsearch'=>"true",),
 array('id'=>"executerid",'name'=>"执行人",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"executername",'name'=>"执行人",'type'=>"popupselectone",'data'=>"model:sys_select_staff(id:executerid,name:executername)",'forsearch'=>"true",'virtualfield'=>"true",),
 array('id'=>"notes",'name'=>"备注",'forsearch'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>