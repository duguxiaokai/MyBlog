<?php return array(
'id'=>"oa_project_report_problem",
'title'=>"影响进度的问题与解决措施",
'tablename'=>"oa_project_report_problem",
'actions'=>"add,del,edit,tableedit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"parentid",'name'=>"项目周报id",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"oid",'name'=>"序号",'type'=>"recordindex",'visibleWhenAdd'=>"false",'virtualfield'=>"true",),
 array('id'=>"problemname",'name'=>"问题名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"method",'name'=>"解决措施",'fieldtype'=>"string",),
 array('id'=>"dotime",'name'=>"解决日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"person",'name'=>"负责人",'fieldtype'=>"string",),
 array('id'=>"help",'name'=>"需要协调的事宜",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",),
),

) ?>