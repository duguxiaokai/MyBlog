<?php return array(
'id'=>"email_domain",
'title'=>"imap设置",
'tablename'=>"email_domain",
'actions'=>"add,del,open,edit,tableedit,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"domain",'name'=>"域名",'fieldtype'=>"string",'length'=>"50",'visibleWhenAdd'=>"false",),
 array('id'=>"imap",'name'=>"IMAP协议",'fieldtype'=>"string",'length'=>"50",'forsearch'=>"true",),
 array('id'=>"imap_port",'name'=>"IMAP端口",'fieldtype'=>"int",'forsearch'=>"true",),
 array('id'=>"pop3",'name'=>"pop3协议",'fieldtype'=>"string",'length'=>"50",'forsearch'=>"true",),
 array('id'=>"pop3_port",'name'=>"POP3端口",'fieldtype'=>"int",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新者",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_EDITIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"datetime",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>