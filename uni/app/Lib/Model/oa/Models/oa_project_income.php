<?php return array(
'id'=>"oa_project_income",
'title'=>"项目收入",
'tablename'=>"oa_project",
'sql'=>"SELECT a.id,a.code,a.name,a.no,(select b.totalamt from biz_order b where b.no=a.no)as totalamt FROM `oa_project` a",
'cols'=>"2",
'fields'=>array(
 array('id'=>"id",'name'=>"项目ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"code",'name'=>"项目号",'fieldtype'=>"string",),
 array('id'=>"name",'name'=>"项目名称",'fieldtype'=>"string",),
 array('id'=>"no",'name'=>"订单号",'fieldtype'=>"string",),
 array('id'=>"totalamt",'name'=>"订单金额",'fieldtype'=>"float",),
),

) ?>