<?php return array(
'id'=>"oa_info_lasted2",
'title'=>"精华帖",
'base'=>"oa_info",
'orderby'=>"SYS_ADDTIME desc",
'pagerows'=>"9",
'actions'=>"open,search",
'wayofcopyfields'=>"onlycopyprop",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"name",'name'=>"标题",),
 array('id'=>"count",'name'=>"点击量",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",),
),

) ?>