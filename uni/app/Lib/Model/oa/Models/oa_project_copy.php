<?php return array(
'id'=>"oa_project_copy",
'title'=>"项目列表",
'tablename'=>"oa_project",
'openurl'=>"oa/Project/projectdetail",
'filter'=>"SYS_ORGID = orgid()",
'open_window_style'=>"newwindow",
'pagerows'=>"10",
'actions'=>",search,page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"项目编号",'fieldtype'=>"string",),
 array('id'=>"name",'name'=>"项目名称",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"executer",'name'=>"执行人",'fieldtype'=>"int",'forsearch'=>"true",),
 array('id'=>"finishper",'name'=>"完成率",'fieldtype'=>"float",),
 array('id'=>"remainingdays",'name'=>"剩余天数",'fieldtype'=>"float",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>",plan:计划中,doing:执行中,done:完成,cancel:取消",'forsearch'=>"true",),
 array('id'=>"notes",'name'=>"备注",'type'=>"textarea",'forsearch'=>"true",),
),

) ?>