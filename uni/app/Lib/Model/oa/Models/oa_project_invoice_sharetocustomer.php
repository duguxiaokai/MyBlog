<?php return array(
'id'=>"oa_project_invoice_sharetocustomer",
'title'=>"客户开票记录",
'base'=>"oa_project_invoice",
'filter'=>"custid = loginuser_customer_id()",
'actions'=>"search,page",

) ?>