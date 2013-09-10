<?php return array(
'id'=>"list_indexorder",
'title'=>"未审核的订单列表",
'tablename'=>"crm_order",
'openurl'=>"CRM/Order/view_order_detail/table/crm_order",
'editurl'=>"default",
'addurl'=>"CRM/Order/neworder",
'filter'=>" status = '待审批' ",
'pagerows'=>"5",
'fields'=>array(
 array('id'=>"no",'name'=>"订单号",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"orderdate",'name'=>"订单日期",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"customer",'name'=>"客户",'forsearch'=>"true",),
 array('id'=>"products",'name'=>"产品",'forsearch'=>"true",),
 array('id'=>"factmoney",'name'=>"成交价",'forsearch'=>"true",),
 array('id'=>"gotmoney",'name'=>"已收款",'forsearch'=>"true",),
 array('id'=>"whogotmoney",'name'=>"收款单位类别",'forsearch'=>"true",),
 array('id'=>"fapiao",'name'=>"发票金额",'forsearch'=>"true",),
 array('id'=>"srctype",'name'=>"来源",'forsearch'=>"true",),
 array('id'=>"agent",'name'=>"代理商",'forsearch'=>"true",),
)
) ?>