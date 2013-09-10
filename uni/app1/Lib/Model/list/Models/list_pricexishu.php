<?php return array(
'id'=>"list_pricexishu",
'title'=>"网络版价格系数表",
'tablename'=>"crm_pricexishu",
'openurl'=>"CRM/Product/showonepricexishu",
'editurl'=>"CRM/Product/editpricexishu",
'addurl'=>"CRM/Product/newpricexishu",
'orderby'=>"amt asc",
'pagerows'=>"100",
'actions'=>"add,del,edit,search,page",
'fields'=>array(
 array('id'=>"amt",'name'=>"节点数",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"xishu",'name'=>"价格系数",'forsearch'=>"true",'style'=>"text-align:center",),
),

) ?>