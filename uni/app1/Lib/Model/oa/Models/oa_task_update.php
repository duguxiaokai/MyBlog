<?php return array(
'id'=>"oa_task_update",
'title'=>"最近任务更新",
'base'=>"oa_project_task",
'filter'=>"SYS_ORGID = orgid()",
'orderby'=>"SYS_EDITTIME desc",
'actions'=>"add,del,open,edit,search,page",

) ?>