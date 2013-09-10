<?php return array(
'id'=>"oa_pm_taskdiyfield",
'title'=>"项目任务自定义字段",
'tablename'=>"oa_project",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"项目名称",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"taskfields",'name'=>"内置字段",'type'=>"checkboxlist",'data'=>"name:任务名称,tasktype:任务类别,begindate:开始,enddate:结束,executer:执行人,finishper:状态,worktime:工时,priority:优先级,attach:附件,score:评分,notes:备注,price:单价,totalprice:总价,unitname:单位,amount:任务量,SYS_EDITTIME:更新时间,SYS_EDITUSER:更新人,SYS_ADDTIME:创建时间,SYS_ADDUSER:创建人",'isvisible'=>"false",),
 array('id'=>"diyfields",'name'=>"自定义字段",'defaultdata'=>"字段1,字段2,字段3,字段4,字段5",),
),

) ?>