<?php return array(
'id'=>"sys_setting",
'title'=>"sys_通用配置表",
'tablename'=>"sys_setting",
'fields'=>array(
 array('id'=>"id",'name'=>"id",'fieldtype'=>"int",),
 array('id'=>"category",'name'=>"项目ID",'fieldtype'=>"string",),
 array('id'=>"name",'name'=>"名称",'fieldtype'=>"string",),
 array('id'=>"code",'name'=>"编码",'fieldtype'=>"string",),
 array('id'=>"setvalue",'name'=>"值",'fieldtype'=>"string",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",),
 array('id'=>"valuetype",'name'=>"valuetype",'fieldtype'=>"string",),
 array('id'=>"updated",'name'=>"updated",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",),
 array('id'=>"SYS_ORGID",'name'=>"SYS_ORGID",'fieldtype'=>"int",'defaultdata'=>"orgid()",),
)
) ?>