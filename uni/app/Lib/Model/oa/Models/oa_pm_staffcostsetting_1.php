<?php return array(
'id'=>"oa_pm_staffcostsetting_1",
'title'=>"项目经理",
'base'=>"oa_pm_staffcostsetting",
'fields'=>array(
array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"string",'length'=>"50",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID = orgid()",'forsearch'=>"true",),
array('id'=>"staffname",'name'=>"员工名称",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",'virtualfield'=>"true",),
array('id'=>"cost",'name'=>"每小时成本",'fieldtype'=>"float",),
array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'length'=>"500",),
array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),




) ?>