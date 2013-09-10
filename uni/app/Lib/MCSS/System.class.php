<?php
header("content-type:text/html; charset=UTF-8"); 
class System extends Model
{
	const copyright="MCSS框架，聚亿企版权所有。";
	const version="0.8";
	
	//addlog($actname)添加
	static public function addlog($actname)
	{
		if (C("mcss_log") && C("mcss_log_sql"))
		{
		$m= new Model();
		$username=System::loginuser();
		$time=date('Y-m-d h:m:s');
		try{
		$sql = "insert into sys_log(action,user,acttime) value('".$actname."','".$username."','".$time."')";
		$n=$m->execute($sql);
		}
		catch(Exception $e)
		{}
		}
	}
	
	static public function log($str)
	{	
		if (C("mcss_log"))
			Log::write($str);			
		}
	
	static public function loginuser()
	{
		return $_SESSION['loginuser'];
	}

	static public function getcustomerid()
	{
		return $_SESSION['customerid'];
	}
	
		
	static public function get_loginuser_right()
	{
		if (!isset($_SESSION['loginuser_funcs']) || C("mcss_checkmenu_everytime"))
			System::setmenuforloginuser();		
		return $_SESSION['loginuser_funcs'];

	}	
	static function setloginuser()
	{
		$username=$_SESSION['loginuser'];
		$sql="select id,email,(select code from sys_role where id=a.roleid) as role,a.roleid,custid from sys_user a where name='$username'";
 		$Model = new Model();
		$rows = $Model->query($sql);
		if (count($rows)>0)
		{
			$_SESSION['loginuserid']= $rows[0]['id'];
			$_SESSION['loginuseremail']= $rows[0]['email'];
			$_SESSION['loginuserrole']= $rows[0]['role'];
			$_SESSION['loginuserroleid']= $rows[0]['roleid'];
			$_SESSION['loginuser_customerid']= $rows[0]['custid'];
			if (!$_SESSION['loginuser_customerid'])
				$_SESSION['loginuser_customerid']=0;
			self::setmenuforloginuser();//获得登录人功能菜单并写入session
			self::setLoginUserFunc();
		}

		$sql="select id,name from sys_staff where username='$username'";
 		$Model = new Model();
		$rows = $Model->query($sql);
		if (count($rows)>0)
		{
			$_SESSION['loginuserstaffid']= $rows[0]['id'];
			$_SESSION['loginusername']= $rows[0]['name'];
			$_SESSION['loginustaffname']= $rows[0]['name'];
		}
		else
		{
			$_SESSION['loginuserstaffid']= "";
			$_SESSION['loginusername']= "";
			$_SESSION['loginustaffname']= "";
		}
		
		 	
	
	}
	static function setLoginUserFunc()
	{
		//$name = $_SESSION['loginuser'];
		$loginuserrole = $_SESSION['loginuserrole'];
		$admin_right=M('sys_right');
		//将取出的权限赋值给$str_right
		if($loginuserrole == 'admin') {
			$str_right = System::getAllMenus();
		}
		else {
			$str_right =System::get_loginuser_right();
		 }
		//将取出的字符串分解成数组
		$arr_right = explode(';',$str_right);
		$num = count($arr_right);
		//将数组数组在一次进行分解
		for($i=0;$i<$num;$i++){
			$arr_rights = explode(':',$arr_right[$i]);
			$arr_rightss = explode(',',$arr_rights[1]);
			if(strlen($arr_right[$i])!= 0){
				array_unshift($arr_rightss,$arr_rights[0]);
				$a[$i]=$arr_rightss;
			}
			
		}
		
		$_SESSION["loginuser_funcs_arr"]=$a;
	}
	
	//获得当前用户的功能菜单json格式数组给前台用
	static function getLoginUserFunc()
	{
		$loginuserrole = $_SESSION['loginuserrole'];
		$admin_right=M('sys_right');
		if($loginuserrole == 'admin') {
			$rows=System::getAllMenuRows();
		}
		else {
			$rows=System::getLoginuserMenuRows();
		 }
		echo json_encode($rows);
	}

	static public function loginuseremail()
	{
		if (!isset($_SESSION['loginuseremail'])){
			//self::setloginuser();
		}
		return $_SESSION['loginuseremail'];
	}
	static public function loginusername()
	{
		if (!isset($_SESSION['loginusername'])){
			//self::setloginuser();
		}
		return $_SESSION['loginusername'];
	}
	
	static public function loginuserrole()
	{
		if (!isset($_SESSION['loginuserrole'])){
			//self::setloginuser();
		}
		return $_SESSION['loginuserrole'];
	}

	static public function loginuserroleid()
	{
		if (!isset($_SESSION['loginuserroleid'])){
			//self::setloginuser();
		}
		return $_SESSION['loginuserroleid'];
	}

	static public function loginuserstaffname()
	{
		if (!isset($_SESSION['loginustaffname'])){
			//self::setloginuser();
		}
		return $_SESSION['loginustaffname'];
	}
	
	static public function loginuserid()
	{
		if (!isset($_SESSION['loginuserid'])){
			//self::setloginuser();
		}
		return $_SESSION['loginuserid'];
	}	
	static public function loginuser_customer_id(){
		if (!isset($_SESSION['loginuser_customerid'])){
			//self::setloginuser();
		}
		return $_SESSION['loginuser_customerid'];
	}

	//获得随机字符串
	static public function randstr()
	{
		return String::rand_string(8,"","");
	}
	
	//生成一个新的id，基于sys_id表的设置，有规律的流水号
	static public function newid($type='',$count=0)
	{
		$r=1;
		$m = new Model();
		$sql="select no from sys_id where type='".$type."'";
		$data = $m->query($sql);
		if (count($data)==1){
			$r=$data[0]['no']+1;
			$m->execute("update sys_id set no=no+1 where type='".$type."'");
		}
		else
		{
			$sql="insert into sys_id (no,type) values(1,'".$type."')";
			$m->execute($sql);
			$r='1';
		}

		while(strlen($r)<$count){
			$r='0'.$r;
		}
		
		return $r;
	}	
	
	//另一种生成一个新的id，基于当前时间和随机数
	function newid2()
	{
		return substr(time()."",5).rand(10000,99999);
	}
	
	static public function getLoginuserMenuRows() 
	{
		$roletype=C("mcss_access_base");
		$name=System::loginuser();
		if ($roletype=='USER'){
			$sql="select functionids,functions from sys_right where user='".$name."'";			
		}
		elseif ($roletype=='ROLE'){
			$userrole=System::loginuserrole();
			$sql="select functionids,functions from sys_right where role='$userrole'";
		}
		elseif($roletype=='ROLE_USER'){
			$userrole=System::loginuserrole();
			$sql="select functionids,functions from sys_right where role='$userrole' or user='".$name."'";
				
		}
		System::log("mcss_access_base:".$sql);
		$m = new Model();
		$rows =$m->query($sql);
		$ids="";
		foreach($rows as $row)
		{
			if ($row['functionids'])
			{
			if ($ids)
				$ids.=",";
			$ids.=$row['functionids'];
			}
		}
		$sql1="select no,name,groupno from sys_function where id in ($ids)";	
		$sql1.=" order by sort";		
		System::log('getLoginuserMenuRows:'.$sql1);				
		return $rows =$m->query($sql1);
	}

	static public function getAllMenuRows() 
	{
    	$sql = "select no,name,groupno from sys_function where status='visible'";		
		$app=$_SESSION['ORGCODE'];			
		if (isset($app))
			$sql.=" and (apps like '%".$app."%' or apps like '%[ALL]%' )";
		$sql.=" order by sort ";
		System::log($sql);
		$m=new Model();
		return $rows=$m->query($sql);
	}
		
    //将admin的权限从表中取出来组成数组返回
	static public function getAllMenus() {
		$admin = M('sys_function');
    	$sql = "select no,name,groupno,groupname from sys_function where status='visible'";		
		$app=$_SESSION['ORGCODE'];			
		if (isset($app))
			$sql.=" and (apps like '%".$app."%' or apps like '%[ALL]%' )";
		$sql.=" order by sort ";
		//System::log('aaa:'.$sql);
		$m=new Model();
		$menu1 =$m->query($sql);
		$menu2 =$m->query($sql);
 
		$length1 = count($menu1);
		$length2 = count($menu2);
		$r="";
		for($i=0;$i<$length1;$i++){
			if ($menu1[$i]["groupno"]=="") {
				$r = trim($r.$menu1[$i]["no"])."|".trim($menu1[$i]["name"]).":";
				for($i2=0;$i2<$length2;$i2++){
					if ($menu2[$i2]["groupno"]== $menu1[$i]["no"] ) {
						$r = trim($r.$menu2[$i2]["no"])."|".$menu2[$i2]["name"].",";
					}
				}
				$r .= ";";
			}
		}
		//计算组合的字符串的长度
		$length = strlen($r);
		//将组合的字符串最后的字符去掉
		$str = substr($r,0,$length-1);
		//得到字符串的最后字符
		$last = substr($r,-1,1);
		//var_dump($last);die;
		if($last == ";") {
			$r=substr($r,0,$length-1);
		}
		return $r;
		
	}
	
	static public function logout() {	
		System::addlog("退出");
    	unset($_SESSION);
		session_destroy();
	}
    //注册
    public function newUser($name,$email,$password){
		$result='err:注册失败.';//注册成功返回空字符串，否则返回格式为“err:输入错误”的错误信息，
		$defaultrole=C("mcss_user_default_role");//新注册用户默认角色
		$sql="select id from sys_role where code='".$defaultrole."'";
		Log::write($sql);
		$roleid=Tool::get1bysql($sql);
		$sql="insert into sys_user(name,email,psw,roleid) values ('$name','$email','$password','$roleid')";
		$m=new Model();
		$n=$m->execute($sql);		
		if ($n>0)
		{
			$_SESSION["loginuser"]=$data['name'];
			self::setloginuser();
			$result="";
		}else{
			$result="err:注册失败。错误信息是：".Tool::parse_error(Tool::get_mysql_error());
		}
		return $result;
    }
    public static function parseExpresion($exprestionStr)
    {
		return Expression::parseExpression($exprestionStr);
    }
	function setmenuforloginuser()
	{
		
		$name=$_SESSION['loginuser'];
		$role=$_SESSION['loginuserrole'];
		if(C("mcss_adminuserallaccess") && ($role == 'admin' || $name == 'admin')) {
			$_SESSION['loginuser_funcs']= System::getAllMenus();			
		}
		else
		{
			$m = new Model();
			
			$roletype=C("mcss_access_base");
			if ($roletype=='USER'){
				$sql="select functionids,functions from sys_right where user='".$name."'";			
			}
			elseif ($roletype=='ROLE'){
				$userrole=System::loginuserrole();
				$sql="select functionids,functions from sys_right where role='$userrole'";
			}
			elseif($roletype=='ROLE_USER'){
				$userrole=System::loginuserrole();
				$sql="select functionids,functions from sys_right where role='$userrole' or user='".$name."'";			
			}
			
			$rows =$m->query($sql);	
			if (count($rows)>0)
			{
				$s="";
				$ids="";
				for($i=0;$i<count($rows);$i++){
					$s .=';'.$rows[$i]["functions"];
					if ($ids)
						$ids.=",";
					$ids.=$rows[$i]["functionids"];
				}
				$_SESSION['loginuser_funcs']=$s;
				$_SESSION['loginuser_func_ids']=$ids;
			}

		}
	
	}
	static function test(){
		echo 'testing';
	}
	
	//设置参数
	static function setSetting(){
		
		
	}
	static function getSetting($code){
		$sql="select setvalue from sys_setting where code='$code'";
		Log::write($sql);
		return Tool::get1bysql($sql);
	}
	
	//获得当前网站的id
	static public function siteid()
	{
		return $_SESSION['cms_siteid'];
	}
	
	static public function getSiteID()
	{
		$group=C("DEFAULT_GROUP");
		$sql="select id from cms_site where code='$group'";
		return Tool::get1bysql($sql);
	}
	//获得当前组织ID
	static public function orgid()
	{
		$r=$_SESSION['ORGID'];
		$str=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$orgid=MCSS_Access::getShareKeyOrgid($str);
		if ($orgid)//如果url中有sharekey并且是正确的，则取sys_share记录中的SYS_ORGID字段值
			$r=$orgid;
		if (!$r)
			$r=-1;
		return $r;
	}	
	//获得当前组织ID
	static public function orgname()
	{
		return $_SESSION['ORGNAME'];
	}	
	//加密密码的方法
	static public function pwdlock($pwd){
		return "se3ck34lkj,".$pwd.",9ss3d4eu0se";
	}
	//解密密码的方法
	static public function pwdkey($lockpwd){
		$pwd = explode(',',$lockpwd);
		return $pwd[1];
	}

	public function openModel($modelid){
		if (!isset($modelid))
			$modelid=$_REQUEST["model"];
		$url= "http://".$_SERVER['HTTP_HOST'].__APP__.'/System/Model/list2?param:modelid='.$modelid;
		header('Location:'.$url);
	}
	
	//发送系统邮件
	public function sendSystemMail($smtpemailto,$mailsubject,$mailbody){
		//$smtpemailto可为多个邮箱,用逗号分隔,如"182530047@qq.com,liuzj@smesforce.com"
		$subject=$mailsubject;
		$content=$mailbody;
		$smtp=C("adminemail_smtp");
		$port=C("adminemail_port");
		$sender=C("adminemail_fromemail");
		$password=C("adminemail_password");
		Vendor("email.emailsend");//导入邮件方法
		$smtp = new smtp($smtp,"25",true,$sender,$password);
		$smtp->smtp_debug =false;
		//$array = sendMail($to,$subject,$content,$smtp,$port,$sender,$password,$attachpath);
		$mail=$smtp->sendmail($smtpemailto, $sender, $subject, $content, "HTML");
		System::log("桔子...".$mail);
		return $mail;
	}

	//设置组织信息
	function setApp($orgid)
	{
		$orgcode=Tool::get1bysql("select code from sys_org where id=$orgid");
		$orgname=Tool::get1bysql("select name from sys_org where id=$orgid");
		
		$_SESSION["ORGID"]=$orgid;
		$_SESSION["ORGNAME"]=$orgname;
		$_SESSION["ORGCODE"]=$orgcode;
		//System::log($_SESSION["ORGCODE"]);
	}
 	
	//获得菜单的url
	static function getFuncRunningUrl($func_row)
	{
		$modelid=$func_row["modelid"];
		$type=$func_row["type"];
		if ($type=="form")
			$url="System/Model/addRecord/model/$modelid/";
		else if ($type=="table")
			$url="System/Model/list2/param:modelid/$modelid/";
		else if ($type=="html")
			$url="System/Model/showhtml/menu_code/".$func_row["no"];
		else
			$url=$func_row['url'];
		return $url;
	}
	//创建一条登陆账号
    static function addAccount($id,$name,$password,$email,$roleid=-1)
	{
		if($name=='')
		{
			return "登陆账号不能为空！";
		}
		if($password=='')
		{
			return "密码不能为空！";
		}
		if (!$name)
		{
			return "账号不能为空！";
		}
	
		if($email=='')
		{
			return "邮箱地址不能为空！";
		}
		$n=Data::sql1("select count(id) from sys_user where name='$name'");
		$s="select count(id) from sys_user where name='$name'";
		System::log($s);
		if ($n>0)
		{
			return "账号'$name'已存在，创建账号失败！";
		}
		$n=Data::sql1("select count(id) from sys_user where email='$email'");
		if ($n>0)
		{
			return "注册邮箱'$email'已存在，创建账号失败！";
		}				
		$psw=md5($password);
		$loginuser=System::loginuser();
		$time=date('Y-m-d H:i:s');
		if(!isset($id))
		{
			$sql="update sys_user (name,psw,email,roleid,SYS_ADDUSER,SYS_ADDTIME,SYS_EDITUSER,SYS_EDITTIME) set('$name','$psw','$email','$roleid','$loginuser','$time','$loginuser','$time')";			
		}
		else
		{
			$sql="insert into sys_user (name,psw,email,roleid,SYS_ADDUSER,SYS_ADDTIME,SYS_EDITUSER,SYS_EDITTIME) values('$name','$psw','$email','$roleid','$loginuser','$time','$loginuser','$time')";			
		}
		System::log($sql);
		return Data::sql($sql);
	}
	//公共的发送信息的方法
	static function sendMessage($title,$content,$open_url,$reciever_ids){
		$orgid = System::orgid();
		$user = System::loginuser();
		$time = Expression::now();
		$m = new Model();
		$ids = explode(",",$reciever_ids);
		for($i = 0;$i < count($ids);$i++){
			$reciever_id = $ids[$i];
			$sql = "insert into sys_message values('','$title','$content','$open_url',$reciever_id,'$user',0,'$user','$time',$orgid)";
			System::log($sql);
			$m->execute($sql);
		}
	}
	static function resumeLog()
	{
		C("mcss_log",$_SESSION["mcss_log"]);
		
	}
	static function stopLog()
	{
		$_SESSION["mcss_log"]=C("mcss_log");
		C("mcss_log",0);
	}
}
?>