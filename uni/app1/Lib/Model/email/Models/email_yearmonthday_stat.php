<?php return array(
'id'=>"email_yearmonthday_stat",
'title'=>"无名",
'tablename'=>"email_mail",
'sql'=>"SELECT maildate AS mail_time, COUNT( * ) AS count
FROM email_mail
where email='chenkj@smesforce.com'
GROUP BY maildate",
'fields'=>array(
 array('id'=>"mail_time",'name'=>"日期",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>