<?php return array(
'id'=>"list_order_indexmy",
'title'=>"最新订单",
'tablename'=>"crm_order",
'addurl'=>"CRM/Order/neworder",
'filter'=>" status='已确认'",
'pagerows'=>"5",
'actions'=>"open,search,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",'forsearch'=>"true",),
 array('id'=>"no",'name'=>"订单号",'forsearch'=>"true",),
 array('id'=>"type",'name'=>"订单类型",'forsearch'=>"true",),
 array('id'=>"custid",'name'=>"用户号",),
 array('id'=>"customer",'name'=>"客户",'forsearch'=>"true",),
 array('id'=>"products",'name'=>"产品",'forsearch'=>"true",),
array('id'=>"netcount",'name'=>"节点数",'forsearch'=>"true",),
array('id'=>"addedmodules",'name'=>"额外模块",'forsearch'=>"true",),
 array('id'=>"factmoney",'name'=>"成交价",'forsearch'=>"true",),
 array('id'=>"orderdate",'name'=>"订单日期",'type'=>"date",'forsearch'=>"true",),
)
) ?>