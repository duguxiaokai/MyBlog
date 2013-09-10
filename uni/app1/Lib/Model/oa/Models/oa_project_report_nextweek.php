<?php return array(
'id'=>"oa_project_report_nextweek",
'title'=>"下周计划",
'tablename'=>"oa_project_report_nextweek",
'actions'=>"add,del,edit,tableedit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"oid",'name'=>"序号",'type'=>"recordindex",'visibleWhenAdd'=>"false",'virtualfield'=>"true",),
 array('id'=>"parentid",'name'=>"项目周报id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"taskname",'name'=>"任务名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"plannum",'name'=>"计划工作量",'fieldtype'=>"string",),
 array('id'=>"plandate",'name'=>"计划完成时间",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"person",'name'=>"负责人",'fieldtype'=>"string",),
 array('id'=>"help",'name'=>"需要协调事宜",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
),

) ?>