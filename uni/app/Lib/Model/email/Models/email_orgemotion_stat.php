<?php return array(
'id'=>"email_orgemotion_stat",
'title'=>"组织邮件情绪变化统计",
'sql'=>"select yearmonth,emotion from email_orgemotion order by yearmonth",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",),
 array('id'=>"yearmonth",'name'=>"时间",),
 array('id'=>"emotion",'name'=>"情绪值",),
),

) ?>