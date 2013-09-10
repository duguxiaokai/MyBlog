<?php return array(
'id'=>"oa_discussion",
'title'=>"项目讨论",
'tablename'=>"oa_discussion",
'actions'=>"add,del,edit,page",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"task_id",'name'=>"任务id",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"project_id",'name'=>"项目id",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"replyto_id",'name'=>"回复时记录针对的主题id",'fieldtype'=>"int",'visibleWhenAdd'=>"false",),
 array('id'=>"content",'name'=>"内容",'fieldtype'=>"string",'length'=>"500",'type'=>"richeditor",'nullable'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",),
),

) ?>