<?php return array(
'id'=>"sys_typeitem",
'title'=>"选项列表",
'notes'=>"显示某类别的选项",
'base'=>"sys_item",
'orderby'=>"orderlist",
'pagerows'=>"50",
'actions'=>"add,del,edit,tableedit,page",
'wayofcopyfields'=>"parent_and_child",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"name",'name'=>"名称",),
 array('id'=>"orderlist",'name'=>"顺序",),
 array('id'=>"type",'name'=>"归类",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>