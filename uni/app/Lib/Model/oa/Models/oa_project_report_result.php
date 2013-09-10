<?php return array(
'id'=>"oa_project_report_result",
'title'=>"本周成果",
'tablename'=>"oa_project_report_result",
'actions'=>"add,del,edit,tableedit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"parentid",'name'=>"项目周报",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"oid",'name'=>"序号",'type'=>"recordindex",'visibleWhenAdd'=>"false",'virtualfield'=>"true",),
 array('id'=>"taskname",'name'=>"任务名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"taskstatue",'name'=>"任务完成状况",'fieldtype'=>"string",),
 array('id'=>"person",'name'=>"参与人员 ",'fieldtype'=>"string",),
 array('id'=>"planstatue",'name'=>"计划内/外",'fieldtype'=>"string",'type'=>"radio",'data'=>"内,外",),
 array('id'=>"day",'name'=>"耗费天",'fieldtype'=>"string",),
 array('id'=>"remark",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
),

) ?>