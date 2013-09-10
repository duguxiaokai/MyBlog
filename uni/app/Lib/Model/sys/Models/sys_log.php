<?php return array(
'id'=>"sys_log",
'title'=>"日志列表",
'tablename'=>"sys_log",
'pagerows'=>"20",
'actions'=>"add,del,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"user",'name'=>"用户",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"acttime",'name'=>"时间",'type'=>"datetime",'forsearch'=>"true",),
 array('id'=>"action",'name'=>"描述",'type'=>"textarea",'forsearch'=>"true",),
),

) ?>