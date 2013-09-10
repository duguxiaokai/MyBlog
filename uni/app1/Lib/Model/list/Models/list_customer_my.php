<?php return array(
'id'=>"list_customer_my",
'title'=>"我代理的客户列表",
'base'=>"list_customer",
'editurl'=>"crm/Customer/editcustomer",
'filter'=>"owner=loginuser() or agent in (select no from crm_agent where username=loginuser())",
'pagerows'=>"20",

) ?>