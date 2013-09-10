<?php return array(
'id'=>"oa_project_all",
'title'=>"所有项目管理",
'base'=>"oa_project_my",
'openurl'=>"Project/projectdetail",
'filter'=>"status<>''",
'orderby'=>"id desc",
'actions'=>"add,del,open,edit,search2,page,",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"name",'name'=>"项目名称",),
 array('id'=>"content",'name'=>"项目概述",),
 array('id'=>"begindate",'name'=>"开始日期",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"executer",'name'=>"项目经理",),
 array('id'=>"status",'name'=>"状态",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建日期",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",),
 array('id'=>"tag",'name'=>"标记",),
),

) ?>