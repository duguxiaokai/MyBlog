<?php
//检查是否已登录，若未登录则转向登录页面。登录页面的网址必须是固定的，及[项目目录]/Index/login
class AdminLoginAction extends Action {
    public function _initialize(){
    	$this->checkLogin();
    }
    function checkLogin()
    {
        header("Content-Type:text/html; charset=utf-8");
        if(!((isset($_SESSION['loginuser']) && ($_SESSION['loginuser'] != null )))){
			$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 			if (substr($url,strlen($url)-1)=="\\" || substr($url,strlen($url)-1)=="/")
			{
				$url=substr($url,0,strlen($url)-1);
			}
			$url=strtr($url, array('/'=>'.xigan.'));
			

 			$app=$_SESSION["ORGCODE"];
			if (!$app)
			{
				$app=C("mcss_app");
			}
			$url_pre=__APP__.'/'.$app.'/Public/adminLogin';
			if (C("mcss_lang") && C("mcss_lang")!='cn')
				$url_pre .="_".C("mcss_lang");
			$url =$url_pre.	'/fromurl/'.$url;			
			redirect($url,0,'',true);
		}
    }

}
?>