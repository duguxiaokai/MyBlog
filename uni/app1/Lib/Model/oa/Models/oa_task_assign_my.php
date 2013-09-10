<?php return array(
'id'=>"oa_task_assign_my",
'title'=>"我未执行的任务",
'base'=>"oa_project_task",
'filter'=>"finishper=0 and executerid = Me() and SYS_ORGID=orgid()",
'groupby'=>"n",
'pagerows'=>"10",
'actions'=>"edit,search,page",
'wayofcopyfields'=>"parent_and_child",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'readonly'=>"true",),
 array('id'=>"projectid",'name'=>"项目",'readonly'=>"true",),
 array('id'=>"tasktype",'name'=>"工作类别",'readonly'=>"true",),
 array('id'=>"name",'name'=>"任务名称",'readonly'=>"true",),
 array('id'=>"worktime",'name'=>"工时",'readonly'=>"true",),
 array('id'=>"begindate",'name'=>"开始日期",'readonly'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'readonly'=>"true",),
 array('id'=>"executer",'name'=>"执行人",'readonly'=>"true",),
 array('id'=>"executerid",'name'=>"执行人",'readonly'=>"true",),
 array('id'=>"detail",'name'=>"任务描述",'readonly'=>"true",),
 array('id'=>"finishper",'name'=>"完成率",),
 array('id'=>"attach",'name'=>"附件",'readonly'=>"true",),
),

) ?>