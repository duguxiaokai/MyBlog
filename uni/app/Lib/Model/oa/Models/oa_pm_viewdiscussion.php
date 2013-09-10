<?php return array(
'id'=>"oa_pm_viewdiscussion",
'title'=>"讨论内容",
'base'=>"oa_discussion",
'actions'=>"add,del,open,edit,search,refresh,page",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"content",'name'=>"内容",'fieldtype'=>"string",'length'=>"5000",'type'=>"richeditor",'forsearch'=>"true",'nullable'=>"false",'width'=>"900px",'height'=>"800px",),
),

) ?>