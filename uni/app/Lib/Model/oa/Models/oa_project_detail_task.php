<?php return array(
'id'=>"oa_project_detail_task",
'title'=>"项目工作分解表",
'base'=>"oa_project_task",
'pagerows'=>"10",
'wayofcopyfields'=>"onlycopyprop",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"projectid",'name'=>"项目",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"任务名称",),
 array('id'=>"tasktype",'name'=>"工作类别",),
 array('id'=>"begindate",'name'=>"开始日期",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"executerid",'name'=>"执行人",),
 array('id'=>"SYS_ADDUSER",'name'=>"下达人",),
 array('id'=>"status",'name'=>"状态",),
 array('id'=>"finishper",'name'=>"完成率",),
 array('id'=>"detail",'name'=>"任务描述",),
 array('id'=>"attach",'name'=>"附件",),
 array('id'=>"notes",'name'=>"备注",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'visibleWhenAdd'=>"false",),
),

) ?>