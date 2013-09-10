<?php return array(
'id'=>"oa_note_my",
'title'=>"便签",
'tablename'=>"oa_note",
'openurl'=>"List/List/viewRecord",
'filter'=>"SYS_ADDUSER = loginuser()",
'actions'=>"add,del,open,search,page",
'wayofcopyfields'=>"onlycopyprop",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"内容",'type'=>"richeditor",'style'=>"width:400px",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>