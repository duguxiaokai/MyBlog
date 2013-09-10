<?php return array(
'id'=>"sys_showitemtype",
'title'=>"显示选项类别",
'fields'=>array(
 array('id'=>"type",'name'=>"选项类别",'type'=>"dropdown",'data'=>"sql:select code,name from sys_itemtype where SYS_ORGID = orgid()",),
),

) ?>