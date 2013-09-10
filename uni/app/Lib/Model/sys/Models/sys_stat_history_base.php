<?php return array(
'id'=>"sys_stat_history_base",
'title'=>"统计表",
'tablename'=>"sys_statdata",
'openurl'=>"Sys/Stat/viewStatData",
'actions'=>"open,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"统计时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"true",'readonly'=>"true",),
 array('id'=>"stat_name",'name'=>"统计名称",'fieldtype'=>"string",'isvisible'=>"false",),
 array('id'=>"stat_time",'name'=>"时间范围",'fieldtype'=>"string",),
 array('id'=>"stat_code",'name'=>"统计类别",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"owner",'name'=>"统计者",'fieldtype'=>"string",'isvisible'=>"true",'visibleWhenAdd'=>"true",),
 array('id'=>"chart",'name'=>"统计图字段",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"html",'name'=>"统计结果",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
)
) ?>