<?php return array(
'id'=>"email_yearmonth_stat",
'title'=>"无名",
'tablename'=>"email_mail",
'sql'=>"SELECT CONCAT( mail_year,  '-', mail_month ) AS yearmonth, COUNT( * ) AS count
FROM email_mail
where email='chenkj@smesforce.com'
GROUP BY CONCAT( mail_year,  '-', mail_month )",
'fields'=>array(
 array('id'=>"yearmonth",'name'=>"年月",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>