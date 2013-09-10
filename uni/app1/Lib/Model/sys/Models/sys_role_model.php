<?php return array(
'id'=>"sys_role_model",
'title'=>"角色模型表",
'notes'=>"每个角色拥有的模型ID列表，可以包括或不包括功能菜单设置中的模型",
'tablename'=>"sys_role_model",
'actions'=>"add,edit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"roleid",'name'=>"角色代码",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"sql:select id,name from sys_role",'forsearch'=>"true",),
 array('id'=>"models",'name'=>"模型ID列表",'fieldtype'=>"string",'type'=>"textarea",'width'=>"400px",'height'=>"600px",),
 array('id'=>"notes",'name'=>"备注",'fieldtype'=>"string",'type'=>"textarea",'width'=>"400px",'height'=>"400px",),
),

) ?>