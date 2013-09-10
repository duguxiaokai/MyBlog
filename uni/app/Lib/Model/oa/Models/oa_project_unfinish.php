<?php return array(
'id'=>"oa_project_unfinish",
'title'=>"未完成的项目",
'base'=>"oa_project",
'filter'=>"status <> 'done' and SYS_ORGID =orgid()",
'actions'=>"open,search,page",
'wayofcopyfields'=>"onlycopyprop",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"code",'name'=>"项目编号",),
 array('id'=>"name",'name'=>"项目名称",),
 array('id'=>"executerid",'name'=>"项目经理",),
 array('id'=>"begindate",'name'=>"开始日期",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"tasktypes",'name'=>"任务分类设置",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",),
 array('id'=>"status",'name'=>"状态",),
 array('id'=>"remainingdays",'name'=>"剩余天数",),
),

) ?>