<?php return array(
'id'=>"oa_project_cost_my",
'title'=>"我的费用",
'base'=>"oa_project_cost",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"project_code",'name'=>"项目号",'type'=>"popupselectone",'data'=>"model:oa_project_copy(code:project_code,name:project_name)",'readonly'=>"true",),
 array('id'=>"project_name",'name'=>"项目名称",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"cost",'name'=>"该任务费用",'fieldtype'=>"float",'type'=>"text",'readonly'=>"true",),
 array('id'=>"paid_date",'name'=>"付款日期",'fieldtype'=>"date",'type'=>"date",'defaultdata'=>"today()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"消费日期",),
),

) ?>