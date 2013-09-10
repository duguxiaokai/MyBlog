<?php return array(
'id'=>"sys_querymodel",
'title'=>"高级过滤器条件名",
'tablename'=>"sys_querymodel",
'pagerows'=>"10",
'actions'=>"add",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"apps",'name'=>"所属应用",'fieldtype'=>"string",'type'=>"dropdown",'data'=>",land:丽兹行,crm:建研,ALL:所有",'isvisible'=>"false",),
 array('id'=>"code",'name'=>"模型编码",'fieldtype'=>"string",'isvisible'=>"false",),
 array('id'=>"name",'name'=>"模型名称",'fieldtype'=>"string",),
 array('id'=>"unit",'name'=>"选项后缀",'isvisible'=>"false",),
 array('id'=>"datatype",'name'=>"选值数据类型",'fieldtype'=>"string",'type'=>"dropdown",'data'=>"s:多选,r:单选,f:数字区间,b:有无,sd:是否,ud:上下",'isvisible'=>"false",),
 array('id'=>"notes",'name'=>"备注",'isvisible'=>"false",),
),

) ?>


