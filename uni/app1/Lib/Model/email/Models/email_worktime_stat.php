<?php return array(
'id'=>"email_worktime_stat",
'title'=>"无名",
'tablename'=>"email_mail",
'sql'=>"SELECT mail_hour AS mail_time, COUNT( * ) AS count
FROM email_mail
where email='chenkj@smesforce.com'
GROUP BY mail_hour",
'fields'=>array(
 array('id'=>"mail_time",'name'=>"时",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>