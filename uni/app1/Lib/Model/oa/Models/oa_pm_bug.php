<?php return array(
'id'=>"oa_pm_bug",
'title'=>"问题反馈",
'tablename'=>"oa_task",
'filter'=>"tasktype='用户反馈'",
'pagerows'=>"5",
'actions'=>"add,open,edit,search,filter,page",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"内容",'fieldtype'=>"string",'type'=>"textarea",'forsearch'=>"true",'nullable'=>"false",'style'=>"width:200px",'prop'=>"OPENRECORD",),
 array('id'=>"attach",'name'=>"图片",'type'=>"image",'style'=>"width:80px",),
 array('id'=>"priority",'name'=>"严重性",'fieldtype'=>"string",'type'=>"radio",'data'=>",g:高,m:中,l:低",'style'=>"width:30px",),
 array('id'=>"finishper",'name'=>"状态",'type'=>"radio",'data'=>"0:未处理,0.5:处理中,1:已解决,-1:不解决,-2:以后处理",'style'=>"width:60px",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'style'=>"width:60px",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'defaultdata'=>"loginuser()",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"projectid",'name'=>"项目ID",'defaultdata'=>"18435",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"tasktype",'name'=>"任务类别",'defaultdata'=>"用户反馈",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>