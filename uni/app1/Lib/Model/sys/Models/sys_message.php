<?php return array(
'id'=>"sys_message",
'title'=>"消息",
'tablename'=>"sys_message",
'pagerows'=>"5",
'fields'=>array(
 array('id'=>"id",'name'=>"主键",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"title",'name'=>"消息标题",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"content",'name'=>"消息内容",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"open_url",'name'=>"消息中包含的网址",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"reciever_id",'name'=>"接收人id",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"source",'name'=>"消息来源",'fieldtype'=>"string",),
 array('id'=>"statue",'name'=>"消息状态",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"0:未读,1:已读",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"消息时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>