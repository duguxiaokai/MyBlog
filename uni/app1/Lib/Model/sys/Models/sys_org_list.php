<?php return array(
'id'=>"sys_org_list",
'title'=>"组织结构列表",
'tablename'=>"sys_org",
'editurl'=>"SYS/Org/savorg",
'addurl'=>"SYS/Org/addorg",
'pagerows'=>"10",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",),
 array('id'=>"name",'name'=>"名称",'forsearch'=>"true",),
 array('id'=>"code",'name'=>"组织代码",'fieldtype'=>"string",),
 array('id'=>"adminuser",'name'=>"管理员",'forsearch'=>"true",),
)
) ?>