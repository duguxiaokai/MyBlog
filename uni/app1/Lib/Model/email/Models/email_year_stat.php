<?php return array(
'id'=>"email_year_stat",
'title'=>"邮件数量按月变化曲线",
'tablename'=>"email_mail",
'sql'=>"select mail_year as year,count(*) as count from email_mail 
where email='chenkj@smesforce.com'
group by mail_year",
'fields'=>array(
 array('id'=>"year",'name'=>"年份",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>