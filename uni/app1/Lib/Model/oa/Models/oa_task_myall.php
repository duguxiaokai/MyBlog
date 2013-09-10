<?php return array(
'id'=>"oa_task_myall",
'title'=>"我未完成的工作",
'base'=>"oa_project_task",
'filter'=>"executerid = Me() and SYS_ORGID=orgid()",
'pagerows'=>"10",
'actions'=>"add,del,open,edit,search,page",

) ?>