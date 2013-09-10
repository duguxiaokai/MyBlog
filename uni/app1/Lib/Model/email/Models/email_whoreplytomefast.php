<?php return array(
'id'=>"email_whoreplytomefast",
'title'=>"谁回复我最快速",
'sql'=>"SELECT senderaddress, round(AVG( reply_time ),1) AS replytime
FROM  `email_mail` 
WHERE reply_time >0
AND reply_time <240
GROUP BY senderaddress
ORDER BY replytime",
'pagerows'=>"50",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"senderaddress",'name'=>"发件人地址",),
 array('id'=>"replytime",'name'=>"回复我时间间隔",),
),

) ?>