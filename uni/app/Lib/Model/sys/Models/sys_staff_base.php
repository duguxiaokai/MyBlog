<?php return array(
'id'=>"sys_staff_base",
'title'=>"员工个人信息",
'base'=>"sys_staff",
'tablename'=>"sys_staff",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"motto",'name'=>"座右铭",'fieldtype'=>"string",),
 array('id'=>"post",'name'=>"我的岗位职责",'fieldtype'=>"string",),
 array('id'=>"aim",'name'=>"个人发展及目标",'fieldtype'=>"string",'type'=>"textarea",),
 array('id'=>"mykpi",'name'=>"我的KPI",'fieldtype'=>"string",'type'=>"textarea",),
 array('id'=>"photos",'name'=>"员工照片",'type'=>"image",),
),

) ?>