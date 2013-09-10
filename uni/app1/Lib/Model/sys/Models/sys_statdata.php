<?php return array(
'id'=>"sys_statdata",
'title'=>"统计数据",
'tablename'=>"sys_statdata",
'openurl'=>"Sys/Stat/viewStatData",
'addurl'=>"Sys/Stat/newStat",
'pagerows'=>"10",
'actions'=>"del,open,edit,search,search2,page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"stat_name",'name'=>"统计名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"stat_code",'name'=>"统计类别",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"owner",'name'=>"统计者",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"chart",'name'=>"统计图字段",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"stat_time",'name'=>"时间段",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"html",'name'=>"统计结果",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"统计时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"true",'visibleWhenAdd'=>"true",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"datatype",'name'=>"数据类别",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"cost_time",'name'=>"耗费时间（分钟）",'fieldtype'=>"real",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
)
) ?>