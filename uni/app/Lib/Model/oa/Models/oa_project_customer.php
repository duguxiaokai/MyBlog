<?php return array(
'id'=>"oa_project_customer",
'title'=>"项目列表",
'tablename'=>"oa_project",
'filter'=>"(SYS_ORGID = orgid() or 1=1) and (custid = loginuser_customer_id())",
'orderby'=>"begindate desc",
'open_window_style'=>"samewindow",
'actions'=>"page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"项目编号",'defaultdata'=>"PROJECT-{yyyy}{mm}{dd}-{newid(project,5)}",'readonly'=>"true",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"name",'name'=>"项目名称",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"today()",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"finishper",'name'=>"完成率",'fieldtype'=>"float",'type'=>"dropdown",'data'=>"0:0%,0.1:10%,0.2:20%,0.3:30%,0.4:40%,0.5:50%,0.6:60%,0.7:70%,0.8:80%,0.85:85%,0.9:90%,0.95:95%,1:100%",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>",plan:计划,doing:执行,done:完成,cancel:取消",'defaultdata'=>"plan",'forsearch'=>"true",),
 array('id'=>"executerid",'name'=>"项目经理",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid() order by name",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"today()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),
'children'=>array(
 array('modelid'=>"oa_project_task",'name'=>"工作分解表",'child_field'=>"projectid",'parent_field'=>"id",),
 array('modelid'=>"oa_project_staff",'name'=>"项目人员表",'child_field'=>"projectid",'parent_field'=>"id",),
)
) ?>