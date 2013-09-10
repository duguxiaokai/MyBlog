<?php return array(
'id'=>"oa_task_execute",
'title'=>"执行中的任务",
'base'=>"oa_project_task",
'filter'=>"status = 'doing' and SYS_ORGID=orgid()",
'actions'=>"add,open,edit,search,page",
'wayofcopyfields'=>"onlycopyprop",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"projectid",'name'=>"项目",),
 array('id'=>"name",'name'=>"任务名称",'forsearch'=>"true",),
 array('id'=>"tasktype",'name'=>"工作类别",),
 array('id'=>"begindate",'name'=>"开始日期",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"detail",'name'=>"任务描述",),
 array('id'=>"status",'name'=>"状态",'visibleWhenAdd'=>"false",),
 array('id'=>"executer",'name'=>"执行人",),
 array('id'=>"finishper",'name'=>"完成状态",'fieldtype'=>"string",'type'=>"radio",'data'=>"0:进行中,1:已完成",'defaultdata'=>"0",),
 array('id'=>"notes",'name'=>"备注",),
 array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>