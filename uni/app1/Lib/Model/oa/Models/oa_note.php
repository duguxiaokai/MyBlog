<?php return array(
'id'=>"oa_note",
'title'=>"便签",
'tablename'=>"oa_note",
'openurl'=>"List/List/viewRecord",
'actions'=>"add,open,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"内容",'fieldtype'=>"string",'type'=>"richeditor",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'defaultdata'=>"loginuser()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>