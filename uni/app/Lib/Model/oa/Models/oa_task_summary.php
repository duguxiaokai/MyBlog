<?php return array(
'id'=>"oa_task_summary",
'title'=>"周报月报总结",
'tablename'=>"oa_task",
'filter'=>"(cat = '周报' or cat = '月报') and tag='3' and SYS_ORGID=orgid()",
'orderby'=>"SYS_EDITTIME desc",
'actions'=>"open,filter",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",'style'=>"width:100px",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"begindate",'name'=>"开始日期",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"executerid",'name'=>"执行人",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",'style'=>"width:100px",),
 array('id'=>"cat",'name'=>"报告类别",'fieldtype'=>"string",),
 array('id'=>"name",'name'=>"总结",'type'=>"textarea",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>