<?php return array(
'id'=>"ruku",
'title'=>"入库登记",
'tablename'=>"crm_ruku",
'orderby'=>"id desc",
'pagerows'=>"5",
'editcondition'=>"{edituser}==loginuser()&&1==2",
'fields'=>array(
 array('id'=>"org",'name'=>"库存机构",'defaultdata'=>"loginuserrole()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"producttype",'name'=>"产品大类",'type'=>"dropdown",'data'=>"sql:select name from sys_item where typename='产品类别'",'readonly'=>"true",'nullable'=>"false",),
 array('id'=>"product",'name'=>"产品名称",'type'=>"dropdown",'data'=>"sql:select name from crm_product order by type,orderindex",'nullable'=>"false",),
 array('id'=>"version",'name'=>"版本",'defaultdata'=>"10版",'nullable'=>"false",),
 array('id'=>"goodnum",'name'=>"入库数量",'fieldtype'=>"int",'nullable'=>"false",),
 array('id'=>"ruku_time",'name'=>"入库时间",'defaultdata'=>"now()",),
 array('id'=>"des",'name'=>"备注",),
 array('id'=>"edituser",'name'=>"登录用户",'defaultdata'=>"loginuser()",'readonly'=>"true",'forsearch'=>"true",),
)
) ?>