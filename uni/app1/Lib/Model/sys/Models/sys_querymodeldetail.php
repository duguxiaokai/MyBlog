<?php return array(
'id'=>"sys_querymodeldetail",
'title'=>"高级过滤器条件值",
'tablename'=>"sys_querymodeldetail",
'editurl'=>"default",
'addurl'=>"default",
'delurl'=>"default",
'pagerows'=>"8",
'actions'=>"add,del,page",
'keyfield'=>"id",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'data'=>"newid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"parentid",'name'=>"条件名",'fieldtype'=>"int",'type'=>"dropdown",'data'=>"sql:select id,name from sys_querymodel",'isvisible'=>"false",'defaultdata'=>''),
 array('id'=>"name",'name'=>"条件值",'fieldtype'=>"string",),
 array('id'=>"sort",'name'=>"显示顺序",'fieldtype'=>"real",'defaultdata'=>"0",),
)
) ?>