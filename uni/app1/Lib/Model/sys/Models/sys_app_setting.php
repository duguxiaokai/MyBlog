<?php return array(
'id'=>"sys_app_setting",
'title'=>"通用设置",
'base'=>"sys_setting",
'filter'=>"SYS_ORGID=orgid()",
'fields'=>array(
 array('id'=>"id",'name'=>"id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"category",'name'=>"类别",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"code",'name'=>"代码",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"name",'name'=>"设置项名称",'fieldtype'=>"string",),
 array('id'=>"setvalue",'name'=>"值",'fieldtype'=>"string",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",),
 array('id'=>"SYS_ORGID",'name'=>"SYS_ORGID",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
)
) ?>