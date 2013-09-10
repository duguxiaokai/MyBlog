<?php return array(
'id'=>"sys_select_staff",
'title'=>"员工列表",
'tablename'=>"sys_staff",
'filter'=>"  (statue=1 or statue='') and SYS_ORGID = orgid()",
'pagerows'=>"10",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",),
 array('id'=>"name",'name'=>"员工姓名",'forsearch'=>"true",),
 array('id'=>"gender",'name'=>"性别",'type'=>"radio",'data'=>"0:未知,F:女,M:男",'forsearch'=>"true",),
 array('id'=>"mobile",'name'=>"电话",'forsearch'=>"true",),
 array('id'=>"notes",'name'=>"备注",'forsearch'=>"true",),
),

) ?>