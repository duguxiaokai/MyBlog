<?php return array(
'id'=>"email_ireplytowhofast",
'title'=>"谁回复我最快速",
'sql'=>"SELECT toaddress, round(AVG( reply_time ),1)  AS replytime
FROM  `email_mail` 
WHERE reply_time >0
AND reply_time <240
GROUP BY toaddress
ORDER BY replytime",
'pagerows'=>"50",
'actions'=>"search,page",
'fields'=>array(
 array('id'=>"toaddress",'name'=>"接收人",),
 array('id'=>"replytime",'name'=>"回复间隔",),
),

) ?>