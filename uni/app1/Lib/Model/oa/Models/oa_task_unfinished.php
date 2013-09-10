<?php return array(
'id'=>"oa_task_unfinished",
'title'=>"我未完成的工作",
'base'=>"oa_project_task",
'filter'=>"finishper<> '1' and executerid = Me() and SYS_ORGID=orgid()",
'pagerows'=>"10",
'actions'=>"add,del,open,edit,search,page",

) ?>