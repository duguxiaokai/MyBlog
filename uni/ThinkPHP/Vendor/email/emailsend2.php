<?php
/**
*
*mail邮件发送文件
*autor：独孤晓凯
**/
function sendMail($to,$subject = "",$body = "",$host="",$port="",$username="",$password="",$path="",$object=""){
    //Author:Jiucool WebSite: http://www.jiucool.com 
    //$to 表示收件人地址 $subject 表示邮件标题 $body表示邮件正文
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区

    require_once('class.phpmailer.php');
    include("class.smtp.php"); 

    $mail = new PHPMailer(); //new一个PHPMailer对象出来
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                                           // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "SSL";                 // 安全协议
    $mail->Host       = $host;      // SMTP 服务器
    $mail->Port       = $port;                   // SMTP服务器的端口号
    $mail->Username   = $username;  // SMTP服务器用户名
    $mail->Password   = $password;            // SMTP服务器密码
    $mail->SetFrom($username, $object);
    $mail->AddReplyTo($username,$object);
    $mail->Subject    = $subject;
   // $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From www.jiucool.com"; // optional, comment out and test
	if($path)
		$mail->AddAttachment($path);      //上传附件的路径
	$array = array();
    for($i=0;$i<count($to);$i++){//将所有的邮件发送都拆分成群发模式
		 $address=$to[$i];
		 $content = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
		 $mail->MsgHTML($content);
		 $mail->ClearAddresses();//对收信地址进行清除
		 $mail->AddAddress($address,"尊敬的客户");
		 if(!$mail->Send()) {
			//echo "发送邮件错误:". $mail->ErrorInfo;
			$array[$i]['errors'] = 1;
			$array[$i]['address'] = $address;
		}else{
			$array[$i]['errors'] = 0;
			$array[$i]['address'] = $address;
		}
	}
	return $array;
}
?>
