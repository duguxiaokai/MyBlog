<?php return array(
'id'=>"list_order_my",
'title'=>"我的订单",
'tablename'=>"crm_order",
'openurl'=>"CRM/Order/view_order_detail/table/crm_order",
'addurl'=>"CRM/Order/neworder",
'filter'=>"owner=loginuser() and  status='已确认'",
'pagerows'=>"20",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"no",'name'=>"订单号",'readonly'=>"true",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"orderdate",'name'=>"订单日期",'type'=>"date",'forsearch'=>"true",),
 array('id'=>"type",'name'=>"订单类型",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"custid",'name'=>"用户号",'isvisible'=>"false",'style'=>"text-align:center",),
 array('id'=>"customer",'name'=>"客户",'isvisible'=>"false",'forsearch'=>"true",),
 array('id'=>"products",'name'=>"产品",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"netcount",'name'=>"节点数",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"addedmodules",'name'=>"额外模块",'forsearch'=>"true",),
 array('id'=>"factmoney",'name'=>"成交价",'fieldtype'=>"float",'forsearch'=>"true",),
 array('id'=>"agent",'name'=>"代理商",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"srctype",'name'=>"订单来源",'data'=>",直销,渠道",'style'=>"text-align:center",),
),

) ?>