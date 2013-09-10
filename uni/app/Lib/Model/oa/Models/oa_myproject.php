<?php return array(
'id'=>"ext_myproject",
'title'=>"项目列表",
'tablename'=>"oa_project",
'editurl'=>"default",
'addurl'=>"default",
'filter'=>"executer = loginuser()",
'pagerows'=>"50",
'actions'=>"add,del,open,edit,search,page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"项目编码",'forsearch'=>"true",),
 array('id'=>"name",'name'=>"项目名称",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"content",'name'=>"项目内容",'fieldtype'=>"string",'type'=>"textarea",),
 array('id'=>"begindate",'name'=>"开始日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"enddate",'name'=>"结束日期",'fieldtype'=>"date",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"sort",'name'=>"分类",'fieldtype'=>"string",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"executer",'name'=>"执行人",'fieldtype'=>"int",'forsearch'=>"true",),
 array('id'=>"finishper",'name'=>"完成率",'fieldtype'=>"float",),
 array('id'=>"remainingdays",'name'=>"剩余天数",'fieldtype'=>"float",),
 array('id'=>"status",'name'=>"状态",'fieldtype'=>"string",'length'=>"10",'type'=>"dropdown",'data'=>",plan:计划中,doing:执行中,done:完成,cancel:取消",'forsearch'=>"true",),
 array('id'=>"notes",'name'=>"备注",'type'=>"textarea",'forsearch'=>"true",),
),
'children'=>array(
 array('modelid'=>"ext_task",'name'=>"工作分解表",'child_field'=>"projectid",'parent_field'=>"id",),
 array('modelid'=>"oa_project_staff",'name'=>"项目员工表",'child_field'=>"projectid",'parent_field'=>"id",),
)
) ?>