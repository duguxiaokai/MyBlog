<?php return array(
'id'=>"email_myemailcount",
'title'=>"谁发邮件给我按数量统计",
'sql'=>"select a.* from (
SELECT senderaddress, COUNT( * ) AS count
FROM  `email_mail` 
WHERE email =  'chenkj@smesforce.com'
GROUP BY senderaddress
) a
where a.count>1
order by count desc",
'pagerows'=>"20",
'actions'=>"page",
'fields'=>array(
 array('id'=>"senderaddress",'name'=>"发件人",),
 array('id'=>"count",'name'=>"邮件数量",),
),

) ?>