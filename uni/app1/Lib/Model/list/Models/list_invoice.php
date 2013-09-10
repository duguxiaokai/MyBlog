<?php return array(
'id'=>"list_invoice",
'title'=>"所有发货单",
'tablename'=>"crm_invoice",
'openurl'=>"CRM/Invoice/add_invoice",
'addurl'=>"CRM/Invoice/add_invoice",
'pagerows'=>"15",
'actions'=>"add,del,open,edit,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"no",'name'=>"特快专递号",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"sendate",'name'=>"发货日期",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"receiver",'name'=>"收件人",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"userno",'name'=>"用户号",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"orderno",'name'=>"订单号",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"type",'name'=>"货物类别",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"way",'name'=>"方式",'forsearch'=>"true",'style'=>"text-align:center",),
),

) ?>