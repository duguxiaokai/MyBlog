<?php
/*
邮件模块首页
*/
class IndexAction extends Action{
	
    function index() {
		if (!$_SESSION['loginuser'] || $_SESSION['loginuser']==null)
		{
    		$this->display('login');
			return;
		}
		
		$appname=$_SESSION["ORGCODE"];
		$this->assign('mcss_app',$appname);
    	$this->display();
    }
    
    function fetchMail()
    {
    }
    
    function sendMail()
    {
    }
    
    function deleteMail()
    {
    }
    
    //下载邮件附件
    function downloadFile()
    {
    }

	
}
?>