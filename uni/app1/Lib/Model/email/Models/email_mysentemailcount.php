<?php return array(
'id'=>"email_mysentemailcount",
'title'=>"我发邮件给谁数量按人统计",
'sql'=>"select a.* from (
SELECT toaddress, COUNT( * ) AS count
FROM  `email_mail` 
WHERE email =  'chenkj@smesforce.com'
GROUP BY toaddress
) a
where a.count>1
order by count desc",
'pagerows'=>"20",
'actions'=>"page",
'fields'=>array(
 array('id'=>"toaddress",'name'=>"收件人",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>