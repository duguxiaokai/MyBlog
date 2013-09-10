<?php
header("content-type:text/html; charset=UTF-8"); 

class ImapAction extends Action{
	function index()
	{
//print_r( getdate('1370402387'));return;
//print_r(getdate(1372064892));return;

//$mbox = imap_open("{imap.qq.com}", "chenkunji@qq.com", "wwwwwww2", OP_HALFOPEN);
//$mbox = imap_open("{pop.exmail.qq.com:110}INBOX", "chenkj@smesforce.com", "]]]]");
$mbox = imap_open("{imap.exmail.qq.com:143}INBOX", "chenkj@smesforce.com", "]]]]");
        $check = imap_check($mbox);
echo $check->Nmsgs;

//$msgnos = imap_search($mbox, 'ALL');
//echo count($msgnos);return;
imap_close($mbox); return;


$list = imap_list($mbox, "{imap.qq.com}", "*");
if (is_array($list)) {
    foreach ($list as $val) {
        echo imap_utf7_decode($val) . "\n";
    }
} else {
    echo "imap_list failed: " . imap_last_error() . "\n";
}
imap_close($mbox);return;

		$account='123@abc.com';
		echo $domain=substr($account,strpos($account,"@")+1);

	}
	function test()
	{
		$this->downloadAccountMail('chenkj@smesforce.com');
		//$this->downloadAccountMail('chenkunji@sina.com');
		//$this->downloadAccountMail('chenkunji@qq.com');
		//$this->downloadAccountMail('chenkunji@hotmail.com');
	}

	function downloadAccountMail($account)
	{
		$accountInfo=$this->getAccountInfo($account);
		//print_r($accountInfo);		return;
		//$this->downloadAccountMailInBox($accountInfo,'INBOX');
		$this->downloadAccountMailInBox($accountInfo,'Sent Messages');
		//Sent Messages INXBOX Drafts Deleted Messages 
		//$this->downloadAccountMailInBox($accountInfo,'OUTBOX');
	}
	
	function getAccountInfo($account)
	{

		$domain=substr($account,strpos($account,"@")+1);
		$sql="select a.email,a.password,b.imap,b.imap_port,b.pop3,b.pop3_port from email_account  a left join email_domain b on b.domain=a.domain where a.email='$account'";
		$rows=Data::getRows("$sql");
		//print_r($rows);exit;
		if (count($rows)>0)
		{
			if ($rows[0]['imap'])
			{
			$r=array('imap'=>trim($rows[0]['imap']),'port'=>trim($rows[0]['imap_port']),'username'=>$account,'password'=>$rows[0]['password']);
			}
			else
			if (strlen($rows[0]['pop3'])>3)
			{
			$r=array('pop3'=>trim($rows[0]['pop3']),'port'=>trim($rows[0]['pop3_port']),'username'=>$account,'password'=>$rows[0]['password']);
			}
			
		}
		else
			$r==array('host'=>'','port'=>'','username'=>'','password'=>'');
		return $r;
		//return $r=array('host'=>'imap.exmail.qq.com','username'=>'chenkj@smesforce.com','password'=']]]]');
	}
	
	function downloadAccountMailInBox($accountInfo,$box='INBOX')
	{
		//echo date(strtotime('20-Jun-2013 18:11:41 +0800'));return;
		$mailList = array();
        $mail = new Uni_Email();
        $host="";
        $pop3=false;
        if ($accountInfo['imap'])
        {
        	$host=$accountInfo['imap'];
        }
        else
        if ($accountInfo['pop3'])
        {
        	$host=$accountInfo['pop3'];
        	$pop3=true;
        }
        
        $connect = $mail->mailConnect($host,$accountInfo['port'],$accountInfo['username'],$accountInfo['password'],$box,false,$pop3);
        if(!$connect) {
	 	   	echo '链接错误：'.imap_last_error();
	 	   	return;
	 	}

            echo '总数：'.$totalCount = $mail->mailTotalCount();
    	    //$headers=imap_headers($connect);
	        //echo count($headers);
        	//$mail->closeMail();return;			
            
			$new_count=0;
			$new_company_count=0;
			$new_contact_count=0;
			$maxmailcount=$totalCount;
			//$maxmailcount=500;
			$maxmailcount=$totalCount-$maxmailcount;
            $sql="delete from email_mail where email='".$accountInfo['username']."' and mailbox='$box'";
	    	
	        Data::sql($sql);
            for ($i=$totalCount; $i > $maxmailcount ; $i--) 
            {
	           // echo "<br /><br />$i:";
                $onemail = $mail->mailHeader($i);
                //$onemail = $headers[$i];
                //echo $onemail['udate'].':'.$onemail['maildate'];
                //continue;
                
                $date_arr=getdate($onemail['udate']);
                //echo '<br />';
                //print_r($date_arr);                
                //echo $onemail['maildate'].'     '.$onemail['udate'];
                //continue;
                
                $maildate=$date_arr['year'].'-'.$date_arr['mon'].'-'.$date_arr['mday'];//.':'.$onemail['subject'];
                //echo ':'.$onemail['udate']."<br />";
                //continue;
            	$sql="insert into email_mail(email,message_id,subject,senderaddress,sendername,toaddress,toname,ccaddress,ccname,secretaddress,secretname,fromaddress,fromname,msgno,date,maildate,udate,mail_year,mail_month,mail_day,mail_hour,sessionid,mailbox)"
	            	."values('".$accountInfo['username']."','".$onemail['message_id']."','".$onemail['subject']."','".$onemail['senderaddress']."','".$onemail['sendername']."','".$onemail['toaddress']."','".$onemail['toname']."','".$onemail['ccaddress']."','".$onemail['ccname']."','".$onemail['secretaddress']."','".$onemail['secretname']."','".$onemail['fromaddress']."','".$onemail['fromname']."','".$onemail['msgno']."','".$onemail['date']."','".$maildate."','".$onemail['udate']."',".$date_arr['year'].",".$date_arr['mon'].",".$date_arr['mday'].",".$date_arr['hours'].",'".$onemail['references']."','$box')";
	            // System::log($sql);
	            $new_count+=Data::sql($sql);
	            continue;
	            
//	            echo $onemail['toaddress'];
				$mailcontacts=$onemail['toaddress'].",".$onemail['ccaddress'];

				$mailname=$onemail['toname'].",".$onemail['ccname'];
				$mails=explode(",",$mailcontacts);
				$mailnames=explode(",",$mailname);
				for($j=0;$j<count($mails);$j++)
				{			
					$onemail=$mails[$j];
					if (!$onemail)	continue;
					$onename=$mailnames[$j];
					
					$domain=explode("@",$onemail);
					$domain=$domain[1];
					$sql="select count(*) from crm_customer where domain='$domain'";
					System::log($sql);
					if (Data::sql1($sql)==0)
					{
						$sql="insert into crm_customer(name,domain) values('".$domain."公司"."','$domain') ";
						//System::log($sql);
						Data::sql($sql);
						$new_company_count++;
					}
					
					$sql="select count(*) from crm_contact where email='$onemail'";
					if (Data::sql1($sql)==0)
					{
						$sql="insert into crm_contact(name,email,emaildomain) values('$onename','$onemail','$domain') ";
						//System::log($sql);
						Data::sql($sql);
						$new_contact_count++;
					}
				
				}
            }
            echo "<br />新增邮件数量：".$new_count."<br />新增公司数量：".$new_company_count."<br />新增联系人数量：".$new_contact_count;
            $mail->closeMail();
	}
}

/*
	            if (strlen($onemail['references'])>2)
	            {
	            foreach($onemail as $title=>$value)
    	        {
    	        	if ($title=='header')
    	        	{
	    	        	echo "<br />";
    	        		print_r($value);    	        		
    	        	}
    	        	else
    	        	{
	            		echo "<br />$title:".$value;
	            	}
	            }
	            }
*/	            

/**
 * NOTICE OF LICENSE
 *
 * THIS SOURCE FILE IS PART OF EVEBIT'S PRIVATE PROJECT.
 * 
 * DO NOT USE THIS FILE IN OTHER PLACE.
 *
 * @category    EveBit_Library
 * @package     Application
 * @author      Chen Qiao <chen.qiao@evebit.com>
 * @version     $$Id: Email.php 175 2011-03-26 09:52:16Z chen.qiao $$
 * @copyright   Copyright (c) 2011 Evebit Inc. China (http://www.evebit.com)
 */

/**
 * Email class
 * 
 * get mail total count,get mail list,get mail content,get mail attach
 * 
 * For a example, if you want to get some specified email list.
 * <code>
 * $mail = new Evebit_Mail();
 * $mail->mailConnect($host,$port,$user,$pass,'INBOX',$ssl);
 * $mail->mailList('5,9:20');
 * </code>
 * 
 * show the five,and nine to twenty mail.
 * <code>
 * $mail->mailList('5,9:20');
 * </code>
 * 
 * @docinfo
 * 
 * @package     Application
 * @author      Chen Qiao <chen.qiao@evebit.com>
 * @version     $$Id: Email.php 175 2011-03-26 09:52:16Z chen.qiao $$
 */
class Uni_Email {
    
    /**
     * @var resource $_connect
     */
    private $_connect;
    /**
     * @var object $_mailInfo
     */
    private $_mailInfo;
    /**
     * @var int $_totalCount
     */
    private $_totalCount;
    /**
     * @var array $_totalCount
     */
    private $_contentType;

    
    /**
     * __construct of the class
     */
    public function __construct() {
        $this->_contentType = array(
            'ez' => 'application/andrew-inset','hqx' => 'application/mac-binhex40',
            'cpt' => 'application/mac-compactpro','doc' => 'application/msword',
            'bin' => 'application/octet-stream','dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream','lzh' => 'application/octet-stream',
            'exe' => 'application/octet-stream','class' => 'application/octet-stream',
            'so' => 'application/octet-stream','dll' => 'application/octet-stream',
            'oda' => 'application/oda','pdf' => 'application/pdf',
            'ai' => 'application/postscript','eps' => 'application/postscript',
            'ps' => 'application/postscript','smi' => 'application/smil',
            'smil' => 'application/smil','mif' => 'application/vnd.mif',
            'xls' => 'application/vnd.ms-excel','ppt' => 'application/vnd.ms-powerpoint',
            'wbxml' => 'application/vnd.wap.wbxml','wmlc' => 'application/vnd.wap.wmlc',
            'wmlsc' => 'application/vnd.wap.wmlscriptc','bcpio' => 'application/x-bcpio',
            'vcd' => 'application/x-cdlink','pgn' => 'application/x-chess-pgn',
            'cpio' => 'application/x-cpio','csh' => 'application/x-csh',
            'dcr' => 'application/x-director','dir' => 'application/x-director',
            'dxr' => 'application/x-director','dvi' => 'application/x-dvi',
            'spl' => 'application/x-futuresplash','gtar' => 'application/x-gtar',
            'hdf' => 'application/x-hdf','js' => 'application/x-javascript',
            'skp' => 'application/x-koan','skd' => 'application/x-koan',
            'skt' => 'application/x-koan','skm' => 'application/x-koan',
            'latex' => 'application/x-latex','nc' => 'application/x-netcdf',
            'cdf' => 'application/x-netcdf','sh' => 'application/x-sh',
            'shar' => 'application/x-shar','swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit','sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc','tar' => 'application/x-tar',
            'tcl' => 'application/x-tcl','tex' => 'application/x-tex',
            'texinfo' => 'application/x-texinfo','texi' => 'application/x-texinfo',
            't' => 'application/x-troff','tr' => 'application/x-troff',
            'roff' => 'application/x-troff','man' => 'application/x-troff-man',
            'me' => 'application/x-troff-me','ms' => 'application/x-troff-ms',
            'ustar' => 'application/x-ustar','src' => 'application/x-wais-source',
            'xhtml' => 'application/xhtml+xml','xht' => 'application/xhtml+xml',
            'zip' => 'application/zip','au' => 'audio/basic','snd' => 'audio/basic',
            'mid' => 'audio/midi','midi' => 'audio/midi','kar' => 'audio/midi',
            'mpga' => 'audio/mpeg','mp2' => 'audio/mpeg','mp3' => 'audio/mpeg',
            'aif' => 'audio/x-aiff','aiff' => 'audio/x-aiff','aifc' => 'audio/x-aiff',
            'm3u' => 'audio/x-mpegurl','ram' => 'audio/x-pn-realaudio','rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin','ra' => 'audio/x-realaudio',
            'wav' => 'audio/x-wav','pdb' => 'chemical/x-pdb','xyz' => 'chemical/x-xyz',
            'bmp' => 'image/bmp','gif' => 'image/gif','ief' => 'image/ief',
            'jpeg' => 'image/jpeg','jpg' => 'image/jpeg','jpe' => 'image/jpeg',
            'png' => 'image/png','tiff' => 'image/tiff','tif' => 'image/tiff',
            'djvu' => 'image/vnd.djvu','djv' => 'image/vnd.djvu','wbmp' => 'image/vnd.wap.wbmp',
            'ras' => 'image/x-cmu-raster','pnm' => 'image/x-portable-anymap',
            'pbm' => 'image/x-portable-bitmap','pgm' => 'image/x-portable-graymap',
            'ppm' => 'image/x-portable-pixmap','rgb' => 'image/x-rgb','xbm' => 'image/x-xbitmap',
            'xpm' => 'image/x-xpixmap','xwd' => 'image/x-xwindowdump','igs' => 'model/iges',
            'iges' => 'model/iges','msh' => 'model/mesh','mesh' => 'model/mesh',
            'silo' => 'model/mesh','wrl' => 'model/vrml','vrml' => 'model/vrml',
            'css' => 'text/css','html' => 'text/html','htm' => 'text/html',
            'asc' => 'text/plain','txt' => 'text/plain','rtx' => 'text/richtext',
            'rtf' => 'text/rtf','sgml' => 'text/sgml','sgm' => 'text/sgml',
            'tsv' => 'text/tab-separated-values','wml' => 'text/vnd.wap.wml',
            'wmls' => 'text/vnd.wap.wmlscript','etx' => 'text/x-setext',
            'xsl' => 'text/xml','xml' => 'text/xml','mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg','mpe' => 'video/mpeg','qt' => 'video/quicktime',
            'mov' => 'video/quicktime','mxu' => 'video/vnd.mpegurl','avi' => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie','ice' => 'x-conference/x-cooltalk',
            'rar' => 'application/x-rar-compressed','zip' => 'application/x-zip-compressed',
            '*'=> 'application/octet-stream','docx' => 'application/msword'
        );
    }
    
    /**
     * Open an IMAP stream to a mailbox
     * 
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $pass
     * @param string $folder
     * @param string $ssl
     * @param string $pop
     * @return resource|bool
     */
    public function mailConnect($host,$port,$user,$pass,$folder="INBOX",$ssl,$pop=false) {
        if ($port)
        	$host.=":".$port;
        if($pop){
            $ssl = $pop.'/'.$ssl.'/novalidate-cert/notls';
            $host.="/pop3";
        } 
        $host="{".$host."}$folder";
        $this->_connect = imap_open($host,$user,$pass);
        if(!$this->_connect) {
//            Evebit_Application::getSession()->addError('cannot connect: ' . imap_last_error());
	 	   	//echo '链接错误：'.imap_last_error();
            return false;    
        } 

        return $this->_connect;
    }
    
    /**
     * Get information about the current mailbox
     *
     * @return object|bool
     */
    public function mailInfo(){
        $this->_mailInfo = imap_mailboxmsginfo($this->_connection);
        if(!$this->_mailInfo) {
            echo "get mailInfo failed: " . imap_last_error();
            return false;
        }
        return $this->_mailInfo;
    }
    
    /**
     * Read an overview of the information in the headers of the given message
     *
     * @param string $msgRange
     * @return array
     */
    public function mailList($msgRange='')
    {
        if ($msgRange) {
            $range=$msgRange;
        } else {
            $this->mailTotalCount();
            $range = "1:".$this->_totalCount;
        }
        $overview  = imap_fetch_overview($this->_connect,$range);
        foreach ($overview  as $val) {
            $mailList[$val->msgno]=(array)$val;
        }
        return $mailList;
    }
    
    /**
     * get the total count of the current mailbox
     *
     * @return int
     */
    public function mailTotalCount(){
        $check = imap_check($this->_connect);
           $this->_totalCount = $check->Nmsgs;
           return $this->_totalCount;
    }
    
    /**
     * Read the header of the message
     *
     * @param string $msgCount
     * @return array
     */
    public function mailHeader($msgCount,$fromlength) {
        $mailHeader = array();
        $header=imap_header($this->_connect,$msgCount,$fromlength);
        $from=$header->from[0];
        $sender=$header->sender[0];
        $to=$header->to[0];
        $cc=$header->cc[0];
        $secret=$header->cc[1];
        $replyTo=$header->reply_to[0];
        if(strtolower($sender->mailbox)!='mailer-daemon' && strtolower($sender->mailbox)!='postmaster') {
            $mailHeader = array(
                'fromaddress'=>strtolower($from->mailbox).'@'.$from->host,
                'fromname'=>$this->chardecode($from->personal),

                'senderaddress'=>strtolower($sender->mailbox).'@'.$sender->host,
                'sendername'=>$this->chardecode($sender->personal),

                'toaddress'=>strtolower($to->mailbox).'@'.$to->host,
                'toname'=>$this->chardecode($to->personal), 

                'ccaddress'=>strtolower($cc->mailbox).'@'.$cc->host,
                'ccname'=>$this->chardecode($cc->personal), 

                'secretaddress'=>strtolower($secret->mailbox).'@'.$secret->host,
                'secretname'=>$this->chardecode($secret->personal), 

                'subject'=>$this->chardecode($header->subject),
                'date'=>$header->date,
                'message_id'=>$header->message_id,
                'msgno'=>$header->Msgno,
                'maildate'=>$header->MailDate,
                'references'=>$header->references,
                
                'size'=>$header->Size,
                'udate'=>$header->udate,
                'header'=>$header
            );
            //print_r($mailHeader);exit;
            /*
                date
                Date
                subject
                Subject
                message_id
                toaddress
                to
                fromaddress
                from
                reply_toaddress
                reply_to
                senderaddress
                sender
                Recent
                Unseen
                Flagged
                Answered
                Deleted
                Draft
                Msgno
                MailDate
                Size
                udate
            */
        }
        return $mailHeader;
    }
    
    function chardecode($headerstr)
    {
	    $arr = imap_mime_header_decode($headerstr);
		return $arr[0]->text;	
    }
   /**
     * decode the subject of chinese
     *@author yang
     *@data 2011-12-30
     * @param string $subject
     * @return sting
     * @todo 将邮件中的乱码转换成正确的中文。（特别是邮件的标题，邮件的内容比较简单些）
     */
    public function subjectDecode($subject) {
        $beginStr = substr($subject, 0, 5);
        if($beginStr == '=?ISO') {
            $separator = '=?ISO-2022-JP';
            $toEncoding = 'ISO-2022-JP';
        } else  {
            $separator = '=?GB2312';
            $toEncoding = 'GB2312';
        }
        $encode = strstr($subject, $separator);
        if ($encode) {
            $explodeArr = explode($separator, $subject);
            $length = count($explodeArr);
            $subjectArr = array();
            for($i = 0; $i < $length / 2; $i++) {
                $subjectArr[$i][] = $explodeArr[$i * 2];
                if (@$explodeArr[$i * 2 + 1]) {
                    $subjectArr[$i][] = $explodeArr[$i * 2 + 1];
                }
            }
            foreach ($subjectArr as $arr) {
                $subSubject = implode($separator, $arr);
                if (count($arr) == 1) {
                    $subSubject = $separator . $subSubject;
                }
                $begin = strpos($subSubject, "=?");
                $end = strpos($subSubject, "?=");
                $beginStr = '';
                $endStr = '';
                if ($end > 0) {
                    if ($begin > 0) {
                        $beginStr = substr($subSubject, 0, $begin);
                    }
                    if ((strlen($subSubject) - $end) > 2) {
                        $endStr = substr($subSubject, $end + 2, strlen($subSubject) - $end - 2);
                    }
                    $str = substr($subSubject, 0, $end - strlen($subSubject));
                    $pos = strrpos($str, "?");
                    $str = substr($str, $pos + 1, strlen($str) - $pos);
                    $subSubject = $beginStr . imap_base64($str) . $endStr;
                    $subSubjectArr[] = iconv ( $toEncoding, 'utf-8', $subSubject );
                    mb_convert_encoding($subSubject, 'utf-8' ,'gb2312,ISO-2022-JP');
                }
            }
            $subject = implode('', $subSubjectArr);
        }
        return $subject;
    }
    
//或者这个也是可以的
    public function subjectDecode2($subject) {
        $separator = '=?GB2312';
        $encode = strstr($subject,$separator);
        if($encode) {
            $explodeArr = explode($separator,$subject);
            $length = count($explodeArr);
            $subjectArr = array();
            for($i = 0;$i < $length/2;$i++) {
                $subjectArr[$i][] = $explodeArr[$i*2];
                if(@$explodeArr[$i*2 + 1]) {
                    $subjectArr[$i][] = $explodeArr[$i*2 + 1];
                }
            }
            foreach ($subjectArr as $arr) {
                $subSubject = implode($separator,$arr);
                if(count($arr) == 1) {
                    $subSubject = $separator.$subSubject;
                }
                $begin = strpos($subSubject ,"=?") ;
                $end = strpos($subSubject , "?=") ;
                $beginStr = '';
                $endStr = '';
                if ($end >0) {
                    if ($begin > 0) {
                        $beginStr = substr($subSubject,0,$begin) ;
                    }
                    if ((strlen($subSubject) - $end)> 2) {
                        $endStr = substr($subSubject,$end + 2 , strlen($subSubject)-$end-2) ;
                    }
                    $str = substr($subSubject,0, $end - strlen($subSubject) );
                    $pos = strrpos($str,"?") ;
                    $str = substr($str,$pos + 1 ,strlen($str)-$pos);
                    $subSubject = $beginStr . imap_base64($str) . $endStr ;
                    $subSubjectArr[] = mb_convert_encoding($subSubject,'utf-8','gbk');
                }
            }
            $subject = implode('',$subSubjectArr);
        }
        return $subject ;
    }
    
    /**
     * Mark a message for deletion from current mailbox
     *
     * @param string $msgCount
     */
    public function mailDelete($msgCount) {
        imap_delete($this->_connect,$msgCount);
    }
    
    /**
     * get attach of the message
     *
     * @param string $msgCount
     * @param string $path
     * @return array
     */
    public function getAttach($msgCount,$path) {
        $struckture = imap_fetchstructure($this->_connect,$msgCount);
        $attach = array();
        if($struckture->parts) {
            foreach($struckture->parts as $key => $value) {
                $encoding=$struckture->parts[$key]->encoding;
                if($struckture->parts[$key]->ifdparameters) {
                    $name=$struckture->parts[$key]->dparameters[0]->value;
                    $message = imap_fetchbody($this->_connect,$msgCount,$key+1);
                    if ($encoding == 0) {
                        $message = imap_8bit($message);    
                    } else if ($encoding == 1){
                        $message = imap_8bit ($message);
                    } else if ($encoding == 2) {
                        $message = imap_binary ($message);
                    } else if ($encoding == 3) {
                        $message = imap_base64 ($message);
                    } else if ($encoding == 4) {
                        $message = quoted_printable_decode($message);
                    }
                    $this->downAttach($path,$name,$message);;
                    $attach[] = $name;
                }
                if($struckture->parts[$key]->parts) {
                    foreach($struckture->parts[$key]->parts as $keyb => $valueb) {
                        $encoding=$struckture->parts[$key]->parts[$keyb]->encoding;
                        if($struckture->parts[$key]->parts[$keyb]->ifdparameters){
                            $name=$struckture->parts[$key]->parts[$keyb]->dparameters[0]->value;
                            $partnro = ($key+1).".".($keyb+1);
                            $message = imap_fetchbody($this->_connect,$msgCount,$partnro);
                            if ($encoding == 0) {
                                $message = imap_8bit($message);
                            } else if ($encoding == 1) {
                                $message = imap_8bit ($message);
                            } else if ($encoding == 2) {
                                $message = imap_binary ($message);
                            } else if ($encoding == 3) {
                                $message = imap_base64 ($message);
                            } else if ($encoding == 4) {
                                $message = quoted_printable_decode($message);
                            }
                            $this->downAttach($path,$name,$message);
                            $attach[] = $name;
                        }
                    }
                }                
            }
        }
        
        return $attach;
    }
    
    /**
     * download the attach of the mail to localhost
     *
     * @param string $filePath
     * @param string $message
     * @param string $name
     */
    public function downAttach($filePath,$name,$message) {
        if(is_dir($filePath)) {
            $fileOpen = fopen($filePath.$name,"w");
        } else {
            mkdir($filePath);
        }
        fwrite($fileOpen,$message);
        fclose($fileOpen);
    }
    
    /**
     * click the attach link to download the attach
     *
     * @param string $id
     */
    public function getAttachData($id,$filePath,$fileName) {
        $nameArr = explode('.',$fileName);
        $length = count($nameArr);
        $contentType = $this->_contentType[$nameArr[$length-1]];
        if(!$contentType) {
            $contentType = $this->_contentType['*'];
        }
          $filePath = chop($filePath);
         if($filePath != ''){
            if(substr($filePath,strlen($filePath)-1,strlen($filePath)) != '/') {
                $filePath .= '/';
            }
               $filePath .= $fileName;
        } else {
            $filePath = $fileName;
          }
          if(!file_exists($filePath)){
            echo 'the file is not exsit';
            return false;
          }
        $fileSize = filesize($filePath);
          header("Content-type: ".$contentType);
          header("Accept-Range : byte ");
          header("Accept-Length: $fileSize");
          header("Content-Disposition: attachment; filename=".$fileName);
          $fileOpen= fopen($filePath,"r");
          $bufferSize = 1024;
          $curPos = 0;
          while(!feof($fileOpen)&&$fileSize-$curPos>$bufferSize) {
                $buffer = fread($fileOpen,$bufferSize);
                echo $buffer;
            $curPos += $bufferSize;
        }
          $buffer = fread($fileOpen,$fileSize-$curPos);
         echo $buffer;
          fclose($fileOpen);
          return true;
    }
    
    /**
     * get the body of the message
     *
     * @param string $msgCount
     * @return string
     */
    public function getBody($msgCount) {
        $body = $this->getPart($msgCount, "TEXT/HTML");
        if ($body == '') {
            $body = $this->getPart($msgCount, "TEXT/PLAIN");
        }
        if ($body == '') {
            return '';
        }
        return $body;
    }
    
    /**
     * Read the structure of a particular message and fetch a particular
     * section of the body of the message
     *
     * @param string $msgCount
     * @param string $mimeType
     * @param object $structure
     * @param string $partNumber
     * @return string|bool
     */
    private function getPart($msgCount, $mimeType, $structure = false, $partNumber = false) {
        if(!$structure) {
            $structure = imap_fetchstructure($this->_connect, $msgCount);
        }
        if($structure) {
            if($mimeType == $this->getMimeType($structure)) {
                if(!$partNumber) {
                    $partNumber = "1";
                }
                $fromEncoding = $structure->parameters[0]->value;
                $text = imap_fetchbody($this->_connect, $msgCount, $partNumber);
                if($structure->encoding == 3) {
                    $text =  imap_base64($text);
                } else if($structure->encoding == 4) {
                    $text =  imap_qprint($text);
                }
                $text = mb_convert_encoding($text,'utf-8',$fromEncoding);
                return $text;
            }
            if($structure->type == 1) {
                while(list($index, $subStructure) = each($structure->parts)) {
                    if($partNumber) {
                        $prefix = $partNumber . '.';
                    }
                    $data = $this->getPart($msgCount, $mimeType, $subStructure, $prefix . ($index + 1));
                    if($data){
                        return $data;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * get the subtype and type of the message structure
     *
     * @param object $structure
     */
    private function getMimeType($structure) {
        $mimeType = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
        if($structure->subtype) {
            return $mimeType[(int) $structure->type] . '/' . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }
    
    /**
     * put the message from unread to read
     *
     * @param string $msgCount
     * @return bool
     */
    public function mailRead($msgCount) {
        $status = imap_setflag_full($this->_connect, $msgCount , "//Seen");
        return $status;
    }
    
    /**
     * Close an IMAP stream
     */
    public    function closeMail() {
        imap_close($this->_connect,CL_EXPUNGE);
    }
    
}
        

 ?>
	