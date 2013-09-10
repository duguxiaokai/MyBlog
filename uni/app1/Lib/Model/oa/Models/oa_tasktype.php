<?php return array(
'id'=>"oa_tasktype",
'title'=>"工作分类表",
'tablename'=>"sys_item",
'filter'=>"type = 'pm_tasktype' and SYS_ORGID=orgid()",
'open_window_style'=>"popup:small",
'actions'=>"add,del,edit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",),
 array('id'=>"type",'name'=>"类别",'defaultdata'=>"pm_tasktype",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>