<?php return array(
'id'=>"list_agent",
'title'=>"代理商列表",
'tablename'=>"crm_agent",
'openurl'=>"CRM/Customer/showoneagent",
'groupby'=>"n",
'open_window_style'=>"popup:large",
'pagerows'=>"20",
'actions'=>"add,del,open,edit,search,page,export",
'cols'=>"2",
'fields'=>array(
 array('id'=>"name",'name'=>"单位名称",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",'style'=>"text-align:center",),
 array('id'=>"no",'name'=>"代理代号",'unique'=>"true",'forsearch'=>"true",'nullable'=>"false",'style'=>"text-align:center",),
 array('id'=>"address",'name'=>"地址",'forsearch'=>"true",),
 array('id'=>"tel",'name'=>"电话",'forsearch'=>"true",'nullable'=>"false",'style'=>"text-align:center",),
 array('id'=>"post",'name'=>"邮编",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"contacts",'name'=>"联系人",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"city",'name'=>"省、市",'forsearch'=>"true",'style'=>"text-align:center",),
 array('id'=>"notes",'name'=>"备注",'type'=>"textarea",'forsearch'=>"true",),
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"username",'name'=>"登录用户名",'fieldtype'=>"string",'type'=>"popupselectone",'data'=>"model:sys_selectuser",'isvisible'=>"true",'visibleWhenAdd'=>"true",'forsearch'=>"true",'style'=>"text-align:center",),
),

) ?>