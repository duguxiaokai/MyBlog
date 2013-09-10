<?php
/*
Email核心处理类
*/
class EmailAction extends Action{
	
    
    function calReplyTime()
    {
    	$rows=Data::getRows("select message_id,sessionid from email_mail where sessionid<>'' and reply_time=0");
    	$n=0;
    	foreach($rows as $row)
    	{
    	$message_id=$row['message_id'];
    	$t=Data::sql1("select udate from email_mail where message_id='".$row["sessionid"]."'");
    	$sql="update email_mail set reply_time=round((udate-$t)/3600,2) where message_id='".$row["message_id"]."'";
    	$n+=Data::sql($sql);
    	}
    	echo $n;
    }
    
	
}
?>