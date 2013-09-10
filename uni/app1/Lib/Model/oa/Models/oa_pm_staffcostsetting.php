<?php return array(
'id'=>"oa_pm_staffcostsetting",
'title'=>"项目人工成本费率设置表",
'tablename'=>"oa_pm_staffcostsetting",
'sql'=>"SELECT a.id,staffid,(select name from sys_staff b where a.staffid=b.id )as staffname,unit,cost,notes,SYS_ORGID FROM oa_pm_staffcostsetting a where a.SYS_ORGID = orgid()",
'actions'=>"add,del,edit,tableedit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"staffid",'name'=>"员工",'fieldtype'=>"string",'length'=>"50",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where  (statue=1 or statue='') and  SYS_ORGID=orgid()",'forsearch'=>"true",),
 array('id'=>"staffname",'name'=>"员工名称",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",'virtualfield'=>"true",),
 array('id'=>"cost",'name'=>"成本",'fieldtype'=>"float",),
 array('id'=>"unit",'name'=>"成本单位",'fieldtype'=>"string",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'length'=>"500",'type'=>"textarea",'forsearch'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>