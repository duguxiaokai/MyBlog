<?php return array(
'id'=>"oa_task_xd_unfinished",
'title'=>"下达未完成工作列表",
'tablename'=>"oa_task",
'filter'=>"SYS_ADDUSER = loginuser() and finishper <>1 and SYS_ORGID=orgid()",
'orderby'=>"begindate desc",
'actions'=>"open,edit,filter,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",'fieldtype'=>"string",),
 array('id'=>"cat",'name'=>"工作类别",'fieldtype'=>"string",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"executerid",'name'=>"执行人",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",),
 array('id'=>"finishper",'name'=>"完成状态",'fieldtype'=>"string",'type'=>"radio",'data'=>"0:进行中,1:已完成",'defaultdata'=>"0",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>