<?php return array(
'id'=>"oa_info_reply",
'title'=>"回复",
'tablename'=>"oa_info_reply",
'fields'=>array(
 array('id'=>"id",'name'=>"回帖ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"oid",'name'=>"与回复帖子关联ID",'fieldtype'=>"int",),
 array('id'=>"content",'name'=>"回复内容",'fieldtype'=>"string",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'defaultdata'=>"MyStaffName()",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"string",'type'=>"SYS_ADDTIME",'readonly'=>"true",),
),

) ?>