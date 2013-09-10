<?php return array(
'id'=>"oa_pm_projectdiscussion",
'title'=>"项目讨论",
'base'=>"oa_discussion",
'actions'=>"add,del,open,edit,search,refresh,page",
'editcondition'=>"SYS_ADDUSER==loginuser()",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"task_id",'name'=>"任务id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"project_id",'name'=>"项目id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"replyto_id",'name'=>"回复时记录针对的主题id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"作者时间",'type'=>"SYS_EDITTIME",'visibleWhenAdd'=>"false",'style'=>"width:100px",),
 array('id'=>"content",'name'=>"内容",'fieldtype'=>"string",'length'=>"5000",'type'=>"richeditor",'forsearch'=>"true",'nullable'=>"false",'width'=>"500px",'height'=>"300px",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>