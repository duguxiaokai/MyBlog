<?php return array(
'id'=>"oa_pm_project_edit",
'title'=>"项目列表",
'tablename'=>"oa_project",
'filter'=>"SYS_ORGID = orgid()",
'orderby'=>"begindate asc",
'open_window_style'=>"samewindow",
'actions'=>"add,del,open,edit,search,page,export",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"项目编号",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"name",'name'=>"项目名称",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"content",'name'=>"项目概述",'fieldtype'=>"string",'type'=>"richeditor",'isvisible'=>"false",),
 array('id'=>"executerid",'name'=>"项目经理",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid() order by name",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>",plan:计划,doing:执行,done:完成,cancel:取消",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"tasktypes",'name'=>"任务分类设置",'type'=>"popupselectone",'data'=>"model:oa_tasktype",),
 array('id'=>"finishper",'name'=>"完成率",'fieldtype'=>"float",'type'=>"dropdown",'data'=>"0:0%,0.1:10%,0.2:20%,0.3:30%,0.4:40%,0.5:50%,0.6:60%,0.7:70%,0.8:80%,0.85:85%,0.9:90%,0.95:95%,1:100%",'isvisible'=>"false",),
 array('id'=>"attach",'name'=>"附件",'fieldtype'=>"string",'type'=>"file",'isvisible'=>"false",),
 array('id'=>"cost_contractmoney",'name'=>"项目金额",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_planed",'name'=>"计划工资成本",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_sales",'name'=>"销售费用",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_managent",'name'=>"办公与管理费用",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_salary",'name'=>"工资成本",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_other",'name'=>"其它成本",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_total",'name'=>"总成本",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_profit",'name'=>"利润",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_tax",'name'=>"税",'fieldtype'=>"float",'readonly'=>"true",),
 array('id'=>"cost_aftertax",'name'=>"税后利润",'fieldtype'=>"float",),
 array('id'=>"notes",'name'=>"备注",'type'=>"textarea",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),
'children'=>array(
 array('modelid'=>"oa_project_task",'name'=>"工作分解表",'child_field'=>"projectid",'parent_field'=>"id",),
 array('modelid'=>"oa_project_staff",'name'=>"项目人员表",'child_field'=>"projectid",'parent_field'=>"id",),
)
) ?>