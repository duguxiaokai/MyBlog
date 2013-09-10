<?php return array(
'id'=>"sys_org",
'title'=>"组织结构表",
'tablename'=>"sys_org",
'pagerows'=>"10",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"name",'name'=>"名称",'forsearch'=>"true",),
 array('id'=>"code",'name'=>"组织代码",'fieldtype'=>"string",'readonly'=>"true",),
 array('id'=>"adminuser",'name'=>"管理员",'forsearch'=>"true",),
 array('id'=>"fullname",'name'=>"简称",'fieldtype'=>"string",),
 array('id'=>"contact",'name'=>"联系人",'fieldtype'=>"string",),
 array('id'=>"tel",'name'=>"公司电话",'fieldtype'=>"string",),
 array('id'=>"moblie",'name'=>"联系人手机",'fieldtype'=>"string",),
 array('id'=>"email",'name'=>"电子邮件",'fieldtype'=>"string",),
 array('id'=>"qq",'name'=>"QQ",'fieldtype'=>"int",),
 array('id'=>"msn",'name'=>"MSN",'fieldtype'=>"int",),
 array('id'=>"website",'name'=>"网站地址",'fieldtype'=>"string",),
 array('id'=>"logo",'name'=>"Logo",'fieldtype'=>"string",'type'=>"image",),
 array('id'=>"login_url",'name'=>"个性化登录网址",'fieldtype'=>"string",),
 array('id'=>"home_url",'name'=>"首页网址",'fieldtype'=>"string",),
),

) ?>