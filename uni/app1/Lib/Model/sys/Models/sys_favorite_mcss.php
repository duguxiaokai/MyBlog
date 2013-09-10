<?php return array(
'id'=>"sys_favorite_mcss",
'title'=>"我的收藏夹",
'tablename'=>"sys_favorite",
'filter'=>"SYS_ORGID=orgid()",
'actions'=>"add,del,open,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",'fieldtype'=>"string",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"url",'name'=>"网址",'fieldtype'=>"string",'type'=>"textarea",'forsearch'=>"true",'nullable'=>"false",'height'=>"50px",),
 array('id'=>"detail",'name'=>"内容",'length'=>"500",'type'=>"textarea",'forsearch'=>"true",),
 array('id'=>"user",'name'=>"收藏者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"project",'name'=>"所属项目",'fieldtype'=>"string",'defaultdata'=>"oa",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"string",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>