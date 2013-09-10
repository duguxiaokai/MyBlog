<?php return array(
'id'=>"crm_whoknowswho",
'title'=>"谁认识谁",
'tablename'=>"crm_contact",
'orderby'=>"custname",
'groupby'=>"y",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'fieldtype'=>"int",'isvisible'=>"false",'prop'=>"PRIMARYKEY;AUTO_INCREMENT",),
 array('id'=>"name",'name'=>"姓名",'fieldtype'=>"string",),
 array('id'=>"custname",'name'=>"客户名称",'fieldtype'=>"string",'isvisible'=>"false",),
 array('id'=>"email",'name'=>"邮箱",'fieldtype'=>"string",),
 array('id'=>"custid",'name'=>"所属客户ID",'fieldtype'=>"int",'isvisible'=>"false",),
 array('id'=>"rank",'name'=>"亲密值",'fieldtype'=>"int",),
 array('id'=>"lastmaildate",'name'=>"上次联系日期",'fieldtype'=>"date",),
),

) ?>