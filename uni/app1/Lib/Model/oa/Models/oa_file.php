<?php return array(
'id'=>"oa_file",
'title'=>"文件",
'tablename'=>"oa_file",
'filter'=>"SYS_ORGID=orgid()",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"文件名",'fieldtype'=>"string",'length'=>"50",'type'=>"file",'forsearch'=>"true",),
 array('id'=>"filepath",'name'=>"路径",'fieldtype'=>"string",'length'=>"255",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"description",'name'=>"描述",'fieldtype'=>"string",'length'=>"255",'type'=>"textarea",'forsearch'=>"true",),
 array('id'=>"dir",'name'=>"目录分类",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",'fieldtype'=>"int",'type'=>"SYS_ORGID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
),

) ?>