<?php
header("content-type:text/html; charset=UTF-8"); 

import('@.ORG.System');
class IndexAction extends Action{
    function index() {
		if ($_SESSION['loginuser']==null)
		{
			$url=__APP__.'/Home/Index/login';
			if (C("mcss_lang") && C("mcss_lang")!='cn')
				$url .="_".C("mcss_lang");
			redirect($url,'1','请登录！');
		}
		
		$a=$_SESSION["loginuser_funcs_arr"];
		$this->assign('dataname',$a);
		$appname=$_SESSION['ORGCODE'];
		$this->assign('mcss_app',$appname);
    	$this->display();
    }

    // 生成验证码  
    public function verify() {  
	   import("@.ORG.Image");  
	   Image::buildImageVerify();  
   }  
   function login_pwd()
   {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display(); 
   }
   //保存用户名到Cookie
   public function saveUserName(){
		$username = $_REQUEST['username'];
		SetCookie("username",$username);
   }
 
     public function test() {	
		print_r($_SESSION);
		echo "<br />COOKIE<br />";
		print_r($_COOKIE);
    }
    //点击安全退出，销毁session，并推出系统到登陆页面
    public function logout() {	
		$app=$_SESSION["ORGCODE"];
		$sql="select login_url from sys_org where code='$app'";
		$login_url=Tool::get1bysql($sql);		
		System::logout();
		echo __APP__."/".$login_url;
		//header("location:".__APP__."/".$login_url);
    }
    public function checkuserpassword() {
		$this->gotoLogin();
	}
	
	/*
	f返回值：
	0：用户名或密码错误，1：正确，2：验证码错误，3：未选择组织，4：此账号未激活,请激活后登录，5：没有开通密码登录功能
	*/
    public function gotoLogin() {
    	$r=0;
		$name = trim($_REQUEST['username']);
		$logintype = strtolower($_REQUEST['logintype']);
		if($logintype=='qq' || $logintype=='autologin')
			$pwd = trim($_REQUEST['password']);
		else
		{
			$pwd = md5(trim($_REQUEST['password']));
		}
		$_SESSION['password']=$pwd;
		$login_option='';		
		if (!$name)
		{
			$sql1="select login_option from sys_user where name = '".$name."'";
			$login_option=Data::get1bysql($sql1);	
			if(!$login_option)
			{
				echo $r;
				return;
			}
		}		
		
		$orgid =$_REQUEST['orgid'];
		$orgcode =$_REQUEST['orgcode'];		
		if ($orgcode)
		{
			$orgid=Data::get1bysql("select id from sys_org where code='$orgcode'");
		}		
		else
		if (!$orgid)
		{
			$orgid=Data::get1bysql("select id from sys_org where code='".C("mcss_app")."'");
		}
		
		System::log("orgid:".$orgid);
		System::setApp($orgid);
			
		
		if(C("mcss_needverifycode") && Session::get('verify') != md5($_REQUEST['verifyImg']))
			$r=2;
		if ($r!=2)
		{
			if(!$login_option)				
				$sql="select orgids from sys_user where (name='$name' or email='$name') and psw='$pwd'";
			else
				$sql="select orgids from sys_user where psw='$pwd' and login_option='P'";			
			$rows = Data::getRows($sql);
			if(count($rows)==1)
			{
				if (!$orgid)
					$r=3;
				else
					$r=1;
			}
				
		}
		if($r==1){
			if(!$login_option)
				$sql = "select name from sys_user where (name='$name' or email='$name') and (status<>'0' or ISNULL(status))";
			else
				$sql = "select name from sys_user where psw='$pwd' and login_option='P' and (status<>'0' or ISNULL(status))";
			$name = Data::get1bysql($sql);
			if(!$name){
				$r = 4;
			}else{
				$_SESSION['loginuser']= $name;
				System::setloginuser();		
			}
		}
		System::log('登录返回码:'.$r);
		echo $r;
    }
	public function testCode(){
		if(Session::get('verify') != md5($_REQUEST['verifyImg']))
			echo 1;
		else
			echo 0;
		 
	}
	
	public function setloginuser()
	{	
		import('@.ORG.System');
		Load('extend');	
		System::setloginuser();		
	}
	

	
	function showloginuserfuncs()
	{
		echo $_SESSION['loginuser_funcs'];//$loginuser_funcs;
	}


    //注册
    public function adduser(){
		
		//$err=System::newUser(trim($_POST['username']),trim($_POST['email']),md5(trim($_POST['password'])));
		$data['name']=$_POST['username'];
		$data['psw']=md5(trim($_POST['password']));
		$data['email']=$_POST['email'];
		$user=M('sys_user');
		$err = $user->add($data);
		if ($err)
		{
			$this->redirect('Index/login',array(),2,"注册成功!");	
		}
		else
		{
			$this->error($err);
		}
	}
//Jquery.validate验证用户名是否存在
    public function checkusername() {
    	$user = M("sys_user");
        $getname = trim($_REQUEST['username']);
    	$data = $user->where("name='".$getname."'")->count();
		if($data>0){
    		die(json_encode(false));
    	}else{
    		die(json_encode(true));
    	}
    }

//Jquery.validatey用户通过邮件设置密码验证邮箱是否存在
    public function checkemail() {
        $user = M("sys_user");
        $getnemail = trim($_REQUEST['email']);
    	$data = $user->where("email='".$getnemail."'")->count();
		if($data>0){
    		die(json_encode(false));
    	}else{
    		die(json_encode(true));
    	}
    }

    public function showserver_side() {
    	$this->display('server_side');
    }


	public function setPageHeaderToSession()
	{
	    //$_SESSION['pageheader']=$_REQUEST['pageheader'];
		//$globals["pageheader"] = $_REQUEST['pageheader'];
		echo "DD";//$_SESSION['name'];//$globals["pageheader"];
	}

    //登陆成功调用此方法，并抛出页面
    function getRights() {
		echo $_REQUEST['pageheader'];
		echo "a";
    }

	public function checkLogin()
	{
		if ($_SESSION['loginuser'] != ""	)
		{
			echo true;
		}
		else{
			echo false;
		}

	}

	 /*
     * 将admin的权限从表中取出来组成数组返回
     */
	public function allRights() {
		import('@.ORG.System');
		Load('extend');
		return System::getAllMenus();
	}
	public function checkedname()
	{
		$username=$_REQUEST['user'];
		$user=M("sys_user");
		$users=$user->where("name='".$username."'")->find();
		if($users == NULL)
		{
			echo '0';
		}else
		{
			$sql="select email from sys_user where name='$username'";
			$result=Data::sql1($sql);
			echo $result;
		}
	}
	
	public function checkedemail()
	{
		$useremail = $_REQUEST['email'];
		$sql = "select email from  sys_user where email='".$useremail."'";
		$result=Data::sql1($sql);
		if($result)
		{
			echo $result;
		}else
		{
			echo '0';
		}
	}


	
	
	public function password_send_email_echo(){	
		$getnemail = trim($_POST['email']);
		$getname = trim($_POST['user']);
		$user=M("sys_user");
		$username=$user->where("email='".$getnemail."'")->getField('name');
		System::log($username);
		$suiji=$this->getCode();		
		$str="会员".$username.":<br><br>您好!为确保您的信息安全,请点击以下链接设置密码:<br><br>";
		$str.="<a href='";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/setpassword/name/".$username."/link/".$suiji."'" ;
		$str.=" target='_blank'>";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/setpassword/name/".$username."/link/".$suiji."";	
		$str.="</a> <br><br>";
		$str.="此链接有效期为三天,并且只能有效使用一次,超过有效期或者您已使用过请重新发送邮件 <br><br>如果点击链接无法激活,请尝试下一方法:<br><br>    第一步:将上述链接拷贝到您计算机浏览器(例如IE)的地址栏中;<br><br>    第二步:按回车（Enter）键<br><br>    如果上述的链接操作失效,请您与客服中心联系<br><br>为了保护您的安全,请勿直接回复本邮件<br><br>    感谢您使用本网站的服务网站";
		$mailsubject ="会员找回密码";
		$smtpemailto = $getnemail;//发送给谁
		$mailbody = $str;//邮件内容
		$data1['time'] =mktime();
		$data1['name'] = $username;
		$data1['link'] =$suiji;
		$data1['status'] =0;
		$user1=D("sys_forgetpassword");
		$user1->data($data1)->add();
		$id=$user1->getLastInsID();
		if(isset($id) && !empty($id))
		{			
			$mail=System::sendSystemMail($smtpemailto,$mailsubject,$mailbody);		
			echo $mail;
		}
	}

	
	public function password_send_email()
	{	
		$getnemail = trim($_POST['email']);
		$getname = trim($_POST['user']);
		if($getnemail == '' || $getname == '')
		{
			$this->error('用户名、邮箱不能为空');
		}else if ($getnemail == '' && $getname == '')
		{
			$this->error('用户名、密码不能为空');
		}else
		{
		$user=M("sys_user");
		$username=$user->where("email='".$getnemail."'")->getField('name');
		/*if(!isset($username) ||empty($username)){
			$this->error("很抱歉，你的输入有误");
		}*/
		$suiji=$this->getCode();		
		$str="会员".$username.":<br><br>您好!为确保您的信息安全,请点击以下链接设置密码:<br><br>";
		$str.="<a href='";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/setpassword/name/".$username."/link/".$suiji."'" ;
		$str.=" target='_blank'>";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/setpassword/name/".$username."/link/".$suiji."";	
		$str.="</a> <br><br>";
		$str.="此链接有效期为三天,并且只能有效使用一次,超过有效期或者您已使用过请重新发送邮件 <br><br>如果点击链接无法激活,请尝试下一方法:<br><br>    第一步:将上述链接拷贝到您计算机浏览器(例如IE)的地址栏中;<br><br>    第二步:按回车（Enter）键<br><br>    如果上述的链接操作失效,请您与客服中心联系<br><br>为了保护您的安全,请勿直接回复本邮件<br><br>    感谢您使用本网站的服务网站";
		$mailsubject ="会员找回密码";
		$smtpemailto = $getnemail;//发送给谁
		$mailbody = $str;//邮件内容
		$data1['time'] =mktime();
		$data1['name'] = $username;
		$data1['link'] =$suiji;
		$data1['status'] =0;
		$user1=D("sys_forgetpassword");
		$user1->data($data1)->add();
		$id=$user1->getLastInsID();
		if(isset($id) && !empty($id))
		{	
			$mail=System::sendSystemMail($smtpemailto,$mailsubject,$mailbody);
			if($mail==1)
			{
				
				$this->assign("jumpUrl",__APP__.'/Index/');
				$this->assign("waitSecond",3);
				$this->success('邮件已发您邮箱请注意查收,此链接三天内有效');
			}else 
			{
				$this->error("邮件发送失败,请重新发送");
			}
		}
	}
	}

	public function setpassword()
	{
		$link = trim($_REQUEST['link']);
		$name1 = trim($_REQUEST['name']);
		$user = M("sys_forgetpassword");
		$sql="SELECT * FROM `sys_forgetpassword` where link='$link'";
		$m1=new Model();
		$result=$m1->query($sql);
		//echo $object;
		if($_POST['psw']){
			$psw= md5(trim($_POST['psw']));
			$psw2= md5(trim($_POST['psw2']));
			if($psw!=$psw2){
			  $this->error("很抱歉，你输入的有误");
			}
			$new_time=mktime();
			$time= trim($_POST['time']);
			if($new_time-$time>5337911386){
				$this->error("此连接已超过有效期");
			}else{
				$name= trim($_POST['name']);
				$status= trim($_POST['status']);
				$name1= trim($_POST['name1']);
				$link= trim($_POST['link']);
				$object = trim($_POST['object']);
				if($name==$name1 && $status!=1){
					
					
					$psw= md5(trim($_POST['psw']));
					$sql="update sys_user set psw='".$psw."' where name='".$name."'";
					$m=new Model();
					
					$updatedRecordCount=$m->execute($sql);
					
					if($updatedRecordCount>0){
						
						$sql1="update sys_forgetpassword set status='1' where link='$link'";
						$m1=new Model();
						$updatedRecordCount1=$m1->execute($sql1);
						if($updatedRecordCount1>0){
							$this->assign("jumpUrl",__APP__.'/Index/');
							$this->success('修改成功');
							
						}else{
							$this->error("很抱歉，修改失败");
						}
					}else if($updatedRecordCount=='0')
					{
							$this->error("很抱歉，您的密码和原密码相同");
					}else{
						$this->error("很抱歉，修改失败");
					}
				}else{
					$this->error("很抱歉，此链接已使用过一次");
				}
			}
		}else{
			
			$this->assign('name1',$name1);
			$this->assign('link',$result[0]['link']);
			$this->assign('status',$result[0]['status']);
			$this->assign('time',$result[0]['time']);
			$this->assign('name',$result[0]['name']);
			$this->display();
		}		
	
	}
	
	
	
	
	public function resetpassword_mail($email)
	{	
		$getnemail =$email;
		$user=M("sys_user");
		$username=$user->where("email='".$getnemail."'")->getField('name');	
		if(!isset($username) ||empty($username)){
			$this->error("很抱歉，你的输入有误");
		}
 		$suiji=$this->getCode();		
		$str=iconv("UTF-8","gb2312","尊敬的".$username.":<br><br>您好!为确保您的信息安全,请点击以下链接设置密码:<br><br>");
		$str.="<a href='";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/resetpassword/name/".$username."/link/".$suiji."'";
		$str.=" target='_blank'>";
		$str.="http://".$_SERVER['HTTP_HOST']."".__URL__."/resetpassword/name/".$username."/link/".$suiji."";	
		$str.="</a> <br><br>";
		$str.=iconv("UTF-8","gb2312","    此链接有效期为三天,并且只能有效使用一次,超过有效期或者您已使用过请重新发送邮件 <br><br>如果点击链接无法激活,请尝试下一方法:<br><br>    第一步:将上述链接拷贝到您计算机浏览器(例如IE)的地址栏中;<br><br>    第二步:按回车（Enter）键<br><br>    如果上述的链接操作失效,请您与客服中心联系<br><br>为了保护您的安全,请勿直接回复本邮件<br><br>    感谢您使用铜道交易服务网站");
		include_once("emailsend.php");
		$smtpserver =C('adminemail_smtp');//SMTP服务器	
		$smtpserver=C('adminemai_landzsmtp');
		$smtpserverport =C('adminemail_port');//SMTP服务器端口
		$smtpusermail =C('adminemail_fromemail'); //SMTP服务器的用户邮箱
		$smtpuser = C('adminemail_username');//SMTP服务器的用户帐号
		$smtppass = C('adminemail_password');//SMTP服务器的用户密码
		$mailsubject =iconv("UTF-8","gb2312","铜道交易网"); //邮件主题
		$smtpemailto = $getnemail;//发送给谁
		$mailbody = $str;//邮件内容
		$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
		##########################################
		//$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
		
		//$smtp->debug = FALSE;//是否显示发送的调试信息	
		$data1['time'] =mktime();
		$data1['name'] = $username;
		$data1['link'] =$suiji;
		$data1['status'] =0;
		$user1=D("sys_forgetpassword");
		$user1->data($data1)->add();
		$id=$user1->getLastInsID();
		test1();
		echo $smtp->test1($username,$mailsubject,$mailbody,$attachpath="");
		if(isset($id)&&!empty($id)&&$smtp->test1($username,$mailsubject,$mailbody,$attachpath=""))
			return '1';
		else
			return '0';
			
	}
	public function send_resetpassword_mail_noredict(){
        $getnemail = trim($_POST['email']);
		echo $this->resetpassword_mail($getnemail);
	}
	
	//发送修改密码邮件
	public function send_resetpassword_mail(){
		$getnemail = trim($_POST['email']);
		if ($this->resetpassword_mail($getnemail)){
			$this->assign("jumpUrl",__APP__.'/Td/Index/');
			$this->assign("waitSecond",5);
			$this->success('邮件已发您邮箱请注意查收,次连接三天内有效');
		}else {
			$this->error("邮件发送失败,请重新发送");
		}
	}
	//随机得到一段数字字母组合
	public function getCode ($length = 80, $mode = 0){
		switch ($mode) {
			case '1':
			$str = '1234567890';
			break;
			case '2':
			$str = 'abcdefghijklmnopqrstuvwxyz';
			break;
			case '3':
			$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			default:
			$str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		}

		$result = '';
		$l = strlen($str)-1;
		$num=0;

		for($i = 0;$i < $length;$i ++){
			$num = rand(0, $l); 
			$a=$str[$num]; 
			$result =$result.$a;
		}
		return  $result;
	}	
	public function getloginuser()
	{
		echo $_SESSION['loginuser'];
	}
	public function getloginuserid()
	{
		echo $_SESSION['loginuserid'];
	}
	
	
	//通过邮箱查找用户设置新密码
	public function resetpassword(){
		
		$link = trim($_REQUEST['link']);
		$name1 = trim($_REQUEST['name']);
		$user = M("sys_forgetpassword");
		$sql="SELECT * FROM `sys_forgetpassword` where link='$link'";
		$m1=new Model();
		$result=$m1->query($sql);
		if($_POST['psw']){
			$psw= md5(trim($_POST['psw']));
			$psw2= md5(trim($_POST['psw2']));
			if($psw!=$psw2){
			  $this->error("很抱歉，你输入的有误");
			}
			$new_time=mktime();
			$time= trim($_POST['time']);
			if($new_time-$time>259200){
				$this->error("此连接已超过有效期");
			}else{
				$name= trim($_POST['name']);
				$status= trim($_POST['status']);
				$name1= trim($_POST['name1']);
				$link= trim($_POST['link']);
				if($name==$name1&& $status!=1){	
					$psw= md5(trim($_POST['psw']));
					$sql="update sys_user set psw='".$psw."' where name='".$name."'";
					$m=new Model();
					$updatedRecordCount=$m->execute($sql);
					if($updatedRecordCount>0){
						$sql1="update sys_forgetpassword set status='1' where link='$link'";
						$m1=new Model();
						$updatedRecordCount1=$m1->execute($sql1);
						if($updatedRecordCount1>0){
							$this->assign("jumpUrl",__APP__.'/Td/Index/');
							$this->success('修改成功');
						}else{
							$this->error("很抱歉，修改失败");
						}
					}else{
						$this->error("很抱歉，修改失败");
					}
				}else{
					$this->error("很抱歉，此链接已使用过一次");
				}
			}
		}else{
			$this->assign("mcss_theme",C("mcss_theme"));
			$this->assign('name1',$name1);
			$this->assign('link',$result[0]['link']);
			$this->assign('status',$result[0]['status']);
			$this->assign('time',$result[0]['time']);
			$this->assign('name',$result[0]['name']);
			$this->display();
		}		
	}
	function login()
	{
		$this->assign("mcss_saas",C("mcss_saas"));
		$this->assign('mcss_app',C("mcss_app"));  
		$this->display();
		
	}	
	function getUserOrgs()
	{
		$field=$_REQUEST["field"];
		$user=$_REQUEST["user"];
		//System::log('sss'.System::loginuserrole());
		//$loginuserrole="admin";//暂时用这个，需要修改
		if($user)
		{
			if ($loginuserrole=="admin")
			{
				$sql="select id,name,code from sys_org";
				$id_field="id";
			}
			else
			{
				$sql="select orgids from sys_user";
				if ($user)
					$sql.=" where name='$user' or email='$user'";
				$orgids=Tool::get1bysql($sql);
				$id_field="id";
				if ($field)
					$id_field=$field;
				$sql="select $id_field,name from sys_org where id in ($orgids)";
			}
			System::log($sql);
			$m=new Model();
			$rows=$m->query($sql);
			$orgids="";
			foreach($rows as $row)
			{
				if ($orgids)
					$orgids.=",";
				$orgids.=$row[$id_field].":".$row["name"];
			}
			echo $orgids;
		}else
			echo '';
	}

	//得到所有组织列表
	function getAllOrgs()
	{
		$field=$_REQUEST["field"];
		$user=$_REQUEST["user"];
		//System::log('sss'.System::loginuserrole());
		$loginuserrole="admin";//暂时用这个，需要修改
		if ($loginuserrole=="admin")
		{
			$sql="select id,name,code from sys_org";
			$id_field="id";
		}
		else
		{
			$sql="select orgids from sys_user";
			if ($user)
				$sql.=" where name='$user' or email='$user'";
			$orgids=Tool::get1bysql($sql);
			$id_field="id";
			if ($field)
				$id_field=$field;		
			$sql="select $id_field,name from sys_org where id in ($orgids)";
		}
		System::log($sql);
		$m=new Model();
		$rows=$m->query($sql);
		$orgids="";
		foreach($rows as $row)
		{
			if ($orgids)
				$orgids.=",";
			$orgids.=$row[$id_field].":".$row["name"];
		}
		echo $orgids;
	}	
	
	function getLoginInfo()
	{
		$logininfo[0]["loginuser"]=System::loginuser();
		$logininfo[0]["loginuserrole"]=System::loginuserrole();
		$logininfo[0]["loginuserroleid"]=System::loginuserroleid();
		$logininfo[0]["loginuserstaffname"]=System::loginuserstaffname();
		$logininfo[0]["loginuserstaffid"]=Organization::MyStaffID();
		$logininfo[0]["loginuserid"]=$_SESSION["loginuserid"];
		//$logininfo[0]["loginuser_dept_staff_ids"]=Organization::MyDeptStaffs_df();//可能返回很大的数据，慎用
		$logininfo[0]["mcss_theme"]=C("mcss_theme");
		$logininfo[0]["mcss_lang"]=C("mcss_lang");
		$logininfo[0]["mcss_app"]=$_SESSION["ORGCODE"];
		$logininfo[0]["mcss_orgid"]=$_SESSION["ORGID"];
		$logininfo[0]["mcss_saas"]=C("mcss_saas");
		$logininfo[0]["password"]=$_SESSION['password'];
		System::log('aaa'.$logininfo[0]["mcss_password"]);
		
		$sql="select home_url from sys_org where code='".$_SESSION["ORGCODE"]."'";
		System::log($sql);
		$logininfo[0]["mcss_home_url"]=Tool::get1bysql($sql);

		$sql="select extension from sys_role where id=".System::loginuserroleid();
		$logininfo[0]["loginuserroleextension"]=Tool::get1bysql($sql);//角色扩张信息
		
		echo json_encode($logininfo);
	}
	function getMyDeptStaffs_df()
	{	
		echo Organization::MyDeptStaffs_df();
	}

	
	//检查登录密码
	public function checklogin_pwd()		
	{
		$name=trim($_REQUEST['name']);		
		$password=md5(trim($_REQUEST['login_pwd']));		
		$newlogin_pwd=md5(trim($_REQUEST['newlogin_pwd']));
		
		$login_pwd='';
		$sql= "select psw from sys_user where name='".$name."'";
		$Model = new Model();
		$data=$Model->query($sql);
		if (count($data)==1)
		{
			$login_pwd=$data[0]['psw'];
		}
		if($password != $login_pwd)
			echo 0;
		else
		if ($newlogin_pwd==$password)
		{
			echo 2;//新密码不能与旧密码相同
		}
		else{
			echo 1;
		}
	}	
	
	//更新登录密码
	public function updatelogin_pwd()		
	{
		$name=$_REQUEST['name'];	
		$newlogin_pwd=md5(trim($_REQUEST['newlogin_pwd']));
		$email_pwd=trim($_REQUEST['email_pwd']);
		$Model = new Model();
		if($email_pwd){
			$email_pwd = System::pwdlock($email_pwd);
			$sql = "update sys_user set email_pwd = '$email_pwd' where name = '$name'";
			$Model->execute($sql);
		}
		$sql= "update sys_user set psw='".$newlogin_pwd."' where name='".$name."'";
		echo $count=$Model->execute($sql);
	}
	
   function mysetting()
   {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display(); 
   }	
   //删除收藏夹
   	public function delFavorite(){
		$name=$_REQUEST['func'];
		$project=$_REQUEST['project'];
		import("@.ORG.System");
		$user = System::loginuser();
		$sql = "delete from sys_favorite where user='$user' and name='$name' and SYS_ORGID = ".System::orgid();
		System::log('删除:'.$sql);
		$m = new Model();
		$result = $m->execute($sql);
		echo $result;
	}
	//保存收藏夹
	public function saveFavorite(){
		$url=$_REQUEST['url'];
		$name=$_REQUEST['func'];
		$project=$_REQUEST['project'];
		import("@.ORG.System");
		$user = System::loginuser();
		$time= date("Y-m-d H:i:s");
		$m = new Model();
		$sql = "select count(id) from sys_favorite where user='$user' and name='$name' and  SYS_ORGID = ".System::orgid();
		$result = Tool::get1bysql($sql);
		if($result == 1){
			$sql = "update sys_favorite set SYS_EDITTIME = '$time' where user='$user' and name='$name' and  SYS_ORGID = ".System::orgid();
			$result = $m->execute($sql);
			if($result > 0){
				echo 2;
			}else{
				echo $result;
			}
			exit;
		}
		$sql = "insert into sys_favorite (`id`, `SYS_ORGID`, `name`, `url`, `user`, `project`, `SYS_EDITTIME`, `detail`) values(null,".System::orgid().",'$name','$url','$user','$project','$time',null)";
		System::log('插入:'.$sql);
		$result = $m->execute($sql);
		echo $result;
	}
	
	function getLoginUserFunc()
	{
		echo System::getLoginUserFunc();
	}
	//单击功能菜单的动作
	public function openMenuUrl() {
		$func_code=$_REQUEST["func_code"];
		$str_right=System::get_loginuser_right();
		$index=strpos($str_right,$func_code."|");
		if (index==-1)
		{
			echo '权限不足!';exit; 
		}
		else {
			$sql="select no,type,modelid,url from sys_function where no='$func_code'";
			$rows=Data::getRows($sql);			
			if (count($rows)>0)
			{
				$row=$rows[0];
				$url=System::getFuncRunningUrl($row);
				
				if ($url)
					Tool::gotourl($url);
					//$this->redirect($url, array(),0,'页面跳转中~');
				else if (!$rows[0]['groupno'])
				{
					echo "请选择下级菜单。";
				}
				else
					echo "该菜单无对应页面。";
			}
			
		}		
	}
	//得到上级菜单
	function getParentMenuIds()
	{
		$func_code=$_REQUEST["func_code"];
		$i=1;
		$t=true;
		$arr=array();
		$arrmenu=array();
		while($t)
		{
			$sql="select groupno from sys_function where no='$func_code'";
			$rows=Data::getRows($sql);
			if (count($rows)>0)
			{
				if($rows[0]['groupno']&&$rows[0]['groupno']!='null')
				{
					$arr[$i]=$rows[0]['groupno'];
					$func_code=$arr[$i];
					$i++;
				}else
					$t=false;
			}else
				$t=false;
		}
		$arr[0]=$_REQUEST["func_code"];
		$n=count($arr);
		for($j=0;$j<$n;$j++)
		{
			$arrmenu[$j]=$arr[$n-$j-1];		//一级菜单下标最小	
		}
		echo json_encode($arrmenu);
	}
	//得到根据密码用户名
	public function getUserNameByPwd()
	{
		$pwd=md5(trim($_REQUEST['pwd']));
		$sql="select name from sys_user where psw='$pwd' and login_option='P'";	
		echo Data::get1bysql($sql);
	}
	//根据QQ登录返回的openid来寻找登录帐号
	public function getUserByOid(){
		$m = new Model();
		$openId = $_REQUEST['openId'];
		$sql = "select count(id) from sys_user where login_key = '$openId'";
		$result = Tool::get1bysql($sql);
		$sql = "select name,psw from sys_user where login_key = '$openId'";
		$data = $m->query($sql);
		$result = $result.'|'.$data[0]['name'].'|'.$data[0]['psw'];
		echo $result;
	}
	//如果用户没有绑定帐号,那么自动根据与QQ关联的ID生成
	public function createUserByOid(){
		$openId = $_REQUEST['openId'];
		$user = $_REQUEST['username'];
		$pwd = md5(trim($_REQUEST['psw']));
		$nickname = $_REQUEST['nickname'];
		$m = new Model();
		$sql = "select count(id) from sys_user where name = '$user'";
		$result = Tool::get1bysql($sql);
		if($result > 0){
			echo 1;exit;
		}
		$sql = "insert into sys_user(name,psw,nickname,login_key)values('$user','$pwd','$nickname','$openId')";
		$result = $m->execute($sql);
		if($result>0)
			echo $user.'|'.$pwd;
	}
	//将QQ码绑定已有的帐号
	public function clockUser(){
		$openId = $_REQUEST['openId'];
		$user = $_REQUEST['username'];
		$pwd = md5(trim($_REQUEST['psw']));
		$nickname = $_REQUEST['nickname'];
		$m = new Model();
		$sql = "select count(id) from sys_user where name = '$user' and psw = '$pwd'";
		$result = Tool::get1bysql($sql);
		if($result > 0){
			$sql = "update sys_user set login_key = '$openId' where name = '$user'";
			$m->execute($sql);
			echo $user.'|'.$pwd;
		}else{
			echo 1;
		}
	}
	//设置存储的密码
	public function setsavepassword(){
		return md5(trim($_REQUEST["pass"]));
	}
	//退出时保存智能选择的cookie
	public function saveSmartCookie(){
		$cookiekey = $_REQUEST["cookiekey"];
		$cookievalue = $_REQUEST["cookievalue"];
		$m = new Model();
		$user = System::loginuser();
		$keyArr = explode('|',$cookiekey);
		$valueArr = explode('|',$cookievalue);
		for($i=0;$i<count($keyArr);$i++){
			$key=$keyArr[$i];
			$value=$valueArr[$i];
			$sql="select count(id) from sys_recent where cookiekey = '$key'";
			$result = Tool::get1bysql($sql);
			if($result==0)	
				$sql="insert into sys_recent values('','$key','$value','$user')";
			else
				$sql="update sys_recent set cookievalue = '$value' where cookiekey = '$key'";
			$m->execute($sql);
		}
	}
}
?>