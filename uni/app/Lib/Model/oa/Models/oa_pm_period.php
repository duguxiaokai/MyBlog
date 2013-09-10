<?php return array(
'id'=>"oa_pm_period",
'title'=>"项目成本核算周期设置",
'tablename'=>"oa_pm_period",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"期间名称",'fieldtype'=>"string",'length'=>"50",),
 array('id'=>"begindate",'name'=>"期间开始日期",'fieldtype'=>"date",),
 array('id'=>"enddate",'name'=>"期间结束日期",'fieldtype'=>"date",),
 array('id'=>"type",'name'=>"期间类别",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>"costinput:成本填报周期,other:其它",),
),

) ?>