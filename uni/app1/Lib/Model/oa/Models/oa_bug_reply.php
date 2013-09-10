<?php return array(
'id'=>"oa_bug_reply",
'title'=>"回复bug列表",
'tablename'=>"oa_bug_reply",
'actions'=>"add,page",
'cols'=>"1",
'sql_after_inserted'=>"update oa_bug set reply_content='record.content',SYS_EDITUSER='record.SYS_ADDUSER',assign_to='record.assign_to',status='record.status',SYS_EDITTIME='record.SYS_ADDTIME' where id=record.pid",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"pid",'name'=>"bug表关联id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDUSER",'name'=>"回复人",'fieldtype'=>"string",'defaultdata'=>"loginuser()",'readonly'=>"true",),
 array('id'=>"content",'name'=>"内容",'fieldtype'=>"string",'type'=>"textarea",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'type'=>"dropdown",'data'=>",1:未解决,2:已解决,3:不解决,3.1:不算问题,3.2:无法重现,4:以后解决,5:需求就这么要求,5.1:设计就这么要求,6:解决中,7:关闭(已验证解决),7.1:暂时关闭",'nullable'=>"false",),
 array('id'=>"image1",'name'=>"回复图片",'fieldtype'=>"string",'type'=>"image",),
 array('id'=>"SYS_ADDTIME",'name'=>"时间",'fieldtype'=>"datetime",'type'=>"datetime",'defaultdata'=>"now()",'visibleWhenAdd'=>"false",),
 array('id'=>"assign_to",'name'=>"指派给",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where  (statue=1 or statue='') and  SYS_ORGID=orgid()",'defaultdata'=>"loginuser()",'nullable'=>"false",),
),

) ?>