<?php return array(
'id'=>"oa_pm_timemanager",
'title'=>"所有项目总进度表",
'tablename'=>"oa_pm_timemanager",
'actions'=>"add,del,edit,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"projectid",'name'=>"项目ID",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from oa_project",),
 array('id'=>"step",'name'=>"阶段",'fieldtype'=>"string",'length'=>"50",),
 array('id'=>"percent",'name'=>"完成率",'fieldtype'=>"float",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'length'=>"500",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新日期",'fieldtype'=>"datetime",),
),

) ?>