<?php return array(
'id'=>"oa_project_addcopy",
'title'=>"项目列表",
'tablename'=>"oa_project",
'filter'=>"SYS_ORGID = orgid()",
'open_window_style'=>"newwindow",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"项目名称",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"executerid",'name'=>"项目经理",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid() order by name",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>",plan:计划,doing:执行,done:完成,cancel:取消",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"finishper",'name'=>"完成率",'fieldtype'=>"float",'type'=>"dropdown",'data'=>"0:0%,0.1:10%,0.2:20%,0.3:30%,0.4:40%,0.5:50%,0.6:60%,0.7:70%,0.8:80%,0.85:85%,0.9:90%,0.95:95%,1:100%",'isvisible'=>"false",),
 array('id'=>"tasktypes",'name'=>"任务分类设置",'type'=>"popupselectone",'data'=>"model:oa_tasktype",),
),

) ?>