<?php return array(
'id'=>"sys_staff_person",
'title'=>"员工基本信息",
'tablename'=>"sys_staff",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"员工姓名",'fieldtype'=>"string",),
 array('id'=>"gender",'name'=>"性别",'fieldtype'=>"string",'type'=>"radio",'data'=>"M:男,F:女",'defaultdata'=>"M",),
 array('id'=>"birthday",'name'=>"出生日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"mobile",'name'=>"手机号码",'fieldtype'=>"string",),
 array('id'=>"tel",'name'=>"联系电话",'fieldtype'=>"string",),
 array('id'=>"msn",'name'=>"MSN",'fieldtype'=>"string",),
 array('id'=>"qq",'name'=>"QQ",'fieldtype'=>"int",),
 array('id'=>"email",'name'=>"Email",'fieldtype'=>"string",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",),
),

) ?>