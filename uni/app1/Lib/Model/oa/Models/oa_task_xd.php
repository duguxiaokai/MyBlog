<?php return array(
'id'=>"oa_task_xd",
'title'=>"下达的任务",
'base'=>"oa_project_task",
'filter'=>"SYS_ADDUSER=loginuser() and SYS_ORGID=orgid()",
'groupby'=>"n",
'pagerows'=>"10",
'actions'=>"add,open,edit,search,page",

) ?>