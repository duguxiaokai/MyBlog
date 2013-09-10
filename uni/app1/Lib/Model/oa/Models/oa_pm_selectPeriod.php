<?php return array(
'id'=>"oa_pm_selectPeriod",
'title'=>"选择项目成本填报周期",
'tablename'=>"oa_pm_period",
'fields'=>array(
 array('id'=>"name",'name'=>"选择期间",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from oa_pm_period where type='costinput' order by id desc ",'forsearch'=>"true",),
),

) ?>