<?php return array(
'id'=>"oa_my_bug",
'title'=>"与我有关的Bug",
'tablename'=>"oa_bug",
'openurl'=>"Oa/Bug/viewBug",
'editurl'=>"Oa/Bug/addBug",
'addurl'=>"Oa/Bug/addBug",
'filter'=>"SYS_ADDUSER = loginuser()  or assign_to=loginuser() or SYS_EDITUSER=loginuser() or (id in (select pid from oa_bug_reply where SYS_ADDUSER=loginuser()))",
'open_window_style'=>"popup:middle",
'actions'=>"search,import,page",
'cols'=>"2",
'editcondition'=>"SYS_ADDUSER==loginuser() || loginuser()=='admin'",
'fields'=>array(
 array('id'=>"id",'name'=>"字段1",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"project_id",'name'=>"所属项目",'type'=>"dropdown",'data'=>"sql:select id,name from oa_project",),
 array('id'=>"subject",'name'=>"标题",'fieldtype'=>"string",'isvisible'=>"true",'visibleWhenAdd'=>"true",'forsearch'=>"true",),
 array('id'=>"content",'name'=>"内容",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
 array('id'=>"image1",'name'=>"图片1",'fieldtype'=>"string",'type'=>"image",'isvisible'=>"false",),
 array('id'=>"sort",'name'=>"类别",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"1:bug,2:改进,3:新功能",'isvisible'=>"false",),
 array('id'=>"priority",'name'=>"优先级",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"1:低,2:中,3:高",'defaultdata'=>"中",),
 array('id'=>"serious",'name'=>"重要性",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"1:低,2:中,3:高",'defaultdata'=>"中",),
 array('id'=>"assign_to",'name'=>"指派给",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select a.name,  b.name as staffusername   from sys_user a left join sys_staff b on   b.username=a.name where a.orgids like   '%orgid()%' order by staffusername",'forsearch'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"1:未解决,2:已解决,3:不解决,4:以后解决,5:需求就这么要求,5.1:设计就这么要求,6:解决中,7:关闭(已验证解决),7.1:暂时关闭",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"reply_content",'name'=>"回复内容",'fieldtype'=>"string",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"回复时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",'width'=>"240px",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>