<?php return array(
'id'=>"oa_bug_genjin",
'title'=>"Bug列表",
'tablename'=>"oa_bug",
'filter'=>"status = '1' and assign_to=loginuser()",
'orderby'=>"id desc",
'open_window_style'=>"popup:middle",
'actions'=>"add,search,search2,filter,export,page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"project_id",'name'=>"所属项目",'type'=>"dropdown",'data'=>"sql:select id,name from oa_project",),
 array('id'=>"subject",'name'=>"标题",'fieldtype'=>"string",'isvisible'=>"true",'visibleWhenAdd'=>"true",'forsearch'=>"true",'nullable'=>"false",),
 array('id'=>"content",'name'=>"详情",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'fieldtype'=>"string",'type'=>"SYS_ADDUSER",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
),

) ?>