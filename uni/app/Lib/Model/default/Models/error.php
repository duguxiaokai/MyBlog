<?php return array(
'id'=>"error",
'title'=>"代理商销售额统计",
'sql'=>"select c.name as agentname,sum(factmoney)  as factmoney FROM crm_order a
left join crm_customer b on b.no=a.custid
left join crm_agent c on c.no=b.agent
where a.orderdate>='{begin_date}' AND a.orderdate<='{end_date}'
GROUP BY c.name",
'fields'=>array(
 array('id'=>"agentname",'name'=>"代理商",),
 array('id'=>"factmoney",'name'=>"实际金额",),
),

) ?>