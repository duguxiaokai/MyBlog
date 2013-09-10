<?php return array(
'id'=>"oa_add_task",
'title'=>"新增任务",
'tablename'=>"oa_task",
'addurl'=>"Oa/Project/addproject",
'actions'=>"add,del,open,edit,search,search2,filter,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"projectid",'name'=>"项目",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from oa_project",'isvisible'=>"false",),
 array('id'=>"name",'name'=>"任务名称",'fieldtype'=>"string",'forsearch'=>"true",),
 array('id'=>"cat",'name'=>"工作类别",'fieldtype'=>"string",'type'=>"checkboxlist",'data'=>"day:日报,week:周报,month:月报",'forsearch'=>"true",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",),
 array('id'=>"executerid",'name'=>"执行人",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff",'defaultdata'=>"Me()",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"plan:计划,doing:执行,done:完成,better:优化,checked:验收,cancel:取消",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",),
 array('id'=>"custid",'name'=>"相关客户ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>