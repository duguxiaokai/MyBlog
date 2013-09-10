<?php return array(
'id'=>"oa_project_cost",
'title'=>"项目资金",
'tablename'=>"oa_project_cost",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"task_id",'name'=>"任务ID",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from oa_task where SYS_ORGID=orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"task_name",'name'=>"任务名称",'type'=>"popupselectone",'data'=>"model:oa_project_task_info(id:task_id,name:task_name,prcode:project_code,prname:project_name,price:unit_price,worktime:amount,totalprice:cost,executerid:executerid)",'forsearch'=>"true",),
 array('id'=>"executerid",'name'=>"执行人",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid()",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"project_code",'name'=>"项目号",'type'=>"popupselectone",'data'=>"model:oa_project_copy(code:project_code,name:project_name)",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"project_name",'name'=>"项目名称",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"amount",'name'=>"任务量",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"unitname",'name'=>"任务量单位",),
 array('id'=>"unit_price",'name'=>"单位成本",'readonly'=>"true",),
 array('id'=>"cost",'name'=>"任务费用",'fieldtype'=>"float",'type'=>"text",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"paid_money",'name'=>"实付金额",'fieldtype'=>"float",'type'=>"text",),
 array('id'=>"paid_date",'name'=>"付款日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"today()",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),
'children'=>array(
 array('modelid'=>"oa_task",'name'=>"字段1",'child_field'=>"projectid",'parent_field'=>"id",),
)
) ?>