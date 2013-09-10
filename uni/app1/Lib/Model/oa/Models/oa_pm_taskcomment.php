<?php return array(
'id'=>"oa_pm_taskcomment",
'title'=>"任务评论",
'base'=>"oa_discussion",
'pagerows'=>"5",
'actions'=>"edit",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"task_id",'name'=>"任务id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"replyto_id",'name'=>"回复时记录针对的主题id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"project_id",'name'=>"项目id",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"content",'name'=>"内容",'type'=>"richeditor",'nullable'=>"false",'style'=>"width:230px",'width'=>"350px",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'style'=>"width:30px",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'style'=>"width:30px",),
),

) ?>