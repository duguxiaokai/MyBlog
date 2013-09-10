<?php return array(
'id'=>"list_student_class",
'title'=>"Student List",
'tablename'=>"yoga_student",
'openurl'=>"default",
'actions'=>"edit",
'fields'=>array(
 array('id'=>"name",'name'=>"Name",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"sex",'name'=>"Sex",'forsearch'=>"true",),
 array('id'=>"age",'name'=>"Age",'type'=>"dropdown",'data'=>"3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"linkman",'name'=>"Contact",'forsearch'=>"true",),
 array('id'=>"telephone",'name'=>"Telephone",'forsearch'=>"true",),
 array('id'=>"address",'name'=>"Email",'forsearch'=>"true",),
 array('id'=>"remark",'name'=>"Remark",'type'=>"text",'forsearch'=>"true",),
),

) ?>