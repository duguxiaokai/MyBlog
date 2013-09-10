<?php return array(
'id'=>"list_order_zhixiao",
'title'=>"直销订单",
'tablename'=>"crm_order",
'addurl'=>"CRM/Order/neworder",
'filter'=>"srctype='直销' and  status='已确认'",
'groupby'=>"n",
'pagerows'=>"20",
'actions'=>"add,del,open,search,search2,filter,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"no",'name'=>"订单号",'readonly'=>"true",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"type",'name'=>"订单类型",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"custid",'name'=>"用户号",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"customer",'name'=>"客户",'forsearch'=>"true",),
 array('id'=>"products",'name'=>"产品",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"factmoney",'name'=>"成交价",'fieldtype'=>"float",'forsearch'=>"true",),
 array('id'=>"orderdate",'name'=>"订单日期",'type'=>"date",'forsearch'=>"true",),
),

) ?>