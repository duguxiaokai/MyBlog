<?php return array(
'id'=>"sys_share",
'title'=>" 共享账号列表",
'tablename'=>"sys_share",
'open_window_style'=>"popup:small",
'actions'=>"add,del,edit,tableedit,search,print,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"shareto",'name'=>"共享帐号列表",'fieldtype'=>"string",'length'=>"500",),
 array('id'=>"url",'name'=>"完整的网址",'fieldtype'=>"string",'length'=>"255",'type'=>"text",'isvisible'=>"true",),
 array('id'=>"sharekey",'name'=>"随机生存的8位字符串",'fieldtype'=>"string",'length'=>"8",'type'=>"text",'isvisible'=>"true",'visibleWhenAdd'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'readonly'=>"true",),
),

) ?>