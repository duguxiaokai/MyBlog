<?php return array(
'id'=>"sys_favorite_my",
'title'=>"我的收藏夹",
'base'=>"sys_favorite",
'filter'=>"user=loginuser()",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"名称",'forsearch'=>"true",),
 array('id'=>"url",'name'=>"网址",'forsearch'=>"true",),
 array('id'=>"user",'name'=>"收藏者",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>