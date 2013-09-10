<?php return array(
'id'=>"oa_smesforce_goodbad",
'title'=>"奖罚记录",
'tablename'=>"smesforce_goodbad",
'pagerows'=>"20",
'actions'=>"add,edit,search,search2,filter,page,export,import",
'editcondition'=>"SYS_ADDUSER==loginuser() || loginuser() =='chenkunji || loginuser() =='admin' ",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"type",'name'=>"奖or罚",'fieldtype'=>"string",'length'=>"2",'type'=>"radio",'data'=>",奖,罚",'forsearch'=>"true",),
 array('id'=>"staffname",'name'=>"员工姓名",'fieldtype'=>"string",'length'=>"20",'data'=>"model:sys_select_staff(name:staffname,id:staffid)",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",'style'=>"width:100px",),
 array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where  (statue=1 or statue='') and  SYS_ORGID=orgid()",),
 array('id'=>"money",'name'=>"奖罚金额（负数表示罚）",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"level",'name'=>"奖罚级别",'fieldtype'=>"string",'length'=>"1",'type'=>"radio",'data'=>",A,B,C,D",'forsearch'=>"true",),
 array('id'=>"reason",'name'=>"原因",'fieldtype'=>"string",'length'=>"200",'type'=>"textarea",'forsearch'=>"true",'style'=>"width:400px",),
 array('id'=>"paid",'name'=>"已兑现",'fieldtype'=>"string",'length'=>"1",'type'=>"dropdown",'data'=>":未兑现,Y:已兑现",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'length'=>"50",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>