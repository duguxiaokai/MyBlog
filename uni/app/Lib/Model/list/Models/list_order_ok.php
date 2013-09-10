<?php return array(
'id'=>"list_order_ok",
'title'=>"解锁订单",
'tablename'=>"crm_order",
'filter'=>" status='已确认'",
'pagerows'=>"15",
'actions'=>"edit,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'visibleWhenAdd'=>"false",),
 array('id'=>"no",'name'=>"订单号",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"status",'name'=>"状态",'forsearch'=>"true",),
),

) ?>