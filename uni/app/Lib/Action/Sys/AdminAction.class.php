<?php
class AdminAction extends CommonAction{
    public function setright(){
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
    }

 
    public function getUser(){
		$staff = M('sys_staff');
		$list = $staff->select();

   		$str='<table border='.'1'.' width='.'300'.' cellspacing='.'0'.' cellpadding='.'5'.'  align='.'center'.'>';
		$str.='<tr><td>用户名<br><input type='.'text'.' name='.'username'.' id='.'username'.' size='.'10'.' onfocus='.'searchStaff()'.' /></td>';
		$str.='<td>姓名</td></tr>';
			foreach($list as $stff ){
				$str.='<tr>';
				$str.='<td>'.$stff['username'].'</td>';
				$str.='<td>'.$stff['name'].'</td>';
				//$str.='<td>'.$stff['username'].'</td>';
				$str.='</tr>';
			}
		$str.='</table>';
    	echo $str;

    }

    
    public function getUsers(){
	    $cus = M("sys_user");
        $data = $cus->select();
        echo json_encode($data);
    }


	//chenkunji
    public function getOrgUsers(){
    	$sql="select name from sys_user where orgids like '%".System::orgid() ."%'";
        echo Data::json($sql);
    }

    public function getFuncsOfUser(){
		$cus = M("sys_right");
        $username = $_REQUEST["username"];
        $data = $cus->field('user,functions,formedit')->where("user='".$username."'")->select();
		echo json_encode($data);
    }

    public function getAllFunctions(){
    	$app=$_SESSION['ORGCODE'];
		$sql="select id,no,name,groupname,groupno from sys_function where status='visible' and apps like '%".$app."%' order by sort";
		System::log("getAllFunctions:".$sql);
		echo Tool::getDataJSON($sql);
    }
	
	//保存用户和角色的权限
    public function saveFunctions(){
		$functions = $_REQUEST["functions"];
		$username = $_REQUEST["username"];
		$rolename = $_REQUEST["rolename"];
		$func_ids = $_REQUEST["func_ids"];
		$querysql='';
		$updatesql='';
		$insertsql='';
		if ($username)
		{
			$querysql="select user from sys_right where user='$username'";
			$updatesql="update sys_right set functions='$functions',functionids='$func_ids'  where user='$username'";
			$insertsql="insert into sys_right(user,functions,functionids) value ('$username','$functions','$func_ids')";
		}
		if ($rolename)
		{
			$querysql="select role from sys_right where role='$rolename'";
			$updatesql="update sys_right set functions='$functions',functionids='$func_ids'  where role='$rolename'";
			$insertsql="insert into sys_right(role,functions,functionids) value ('$rolename','$functions','$func_ids')";
		}

        $functions=substr($funcs,1);
		$r=0;

		if (Tool::executesql($querysql)>0)
		{
			$r=Tool::executesql($updatesql);
		}
		else
		{
			$r=Tool::executesql($insertsql);
		} 
		
 
		$err=Tool::get_my_error();
		
		if ($r>0)
			echo 'ok';
		if ($r==0 && !mysql_error())
			echo '0';

		if (mysql_error())
			echo 'err:'.$err;

		
    }	
	
	
	
	
	
    //获得指定用户的快捷操作--
    public function getShortcutsOfUser(){

		$user=$_REQUEST['user'];
		echo $func=$this->getShortcutsOfOneUser($user);
		
    }

    //获得当前用户的快捷操作--
    public function getShortcutsOfLoginUser(){

		import("@.ORG.System");
		$user = System::loginuser();
		echo $func=$this->getShortcutsOfOneUser($user);
    }
    

	public function getShortcutUrls(){
		import("@.ORG.System");
		$project = $_REQUEST['project'];
		$user = System::loginuser();
		$sql = "select name,url from sys_favorite where user = '$user' and SYS_ORGID = ".System::orgid()." order by SYS_EDITTIME desc";
		$m = new Model();
		$rows =$m->query($sql);
		echo json_encode($rows);
	}

    //获得某个用户的快捷操作--
    public function getShortcutsOfOneUser($user){
		
		$cus = M("sys_shortcut");
		
		if (!isset($user))
		{
			import("@.ORG.System");
			$user = System::loginuser();
		}
		     
        $sql="select funcs from sys_shortcut where user='".$user."'";
        $m=new Model();
        $data =$m->query($sql);
        
        if (!isset($user) && count($data)==0)
        {
 			$user = "admin";
	        $sql="select funcs from sys_shortcut where user='".$user."'";
	        $m=new Model();
	        $data =$m->query($sql);
        }
        
        if (count($data)>0)
			return  $data[0]["funcs"];
		else
			return '';
    }  
      
    //保持功能快捷
    public function saveFunctionShortcut(){
		$funcs = $_REQUEST["functions"];
		$user = $_REQUEST["username"];
		if (!isset($user))
		{
			import("@.ORG.System");
			$user = System::loginuser();
		}
		$rights = M("sys_shortcut");
		$data1["funcs"]=$funcs;
		$data1["user"]=$user;

		$count = $rights->where("user='".$user."'")->save($data1);
		if ($count==0)	
    	{
			$r = $rights->add($data1);
		}
		$result = 'ok';
		echo $result;
    }

	public function list_user() {
		System::openModel("sys_user");
    }

	public function list_log() {	
		System::openModel("sys_log");
    }
    
    public function newuser() {
        $this->display();
    }

    public function savenewuser() {
        $data['name'] = trim($_POST['name']);
    	//$data['psw'] = trim($_POST['psw']);
    	$data['psw'] = md5(trim($_POST['psw']));
    	$data['email'] = trim($_POST['email']);
    	$user=D("sys_user");
    	if(!$user->create()){
            echo "<script>window.location.history.back();</script>";
 		}else{
			if($user->data($data)->add()){
                $Model = new Model();
                $sql = "select id from sys_user order by id desc limit 1";
                $userid=$Model->query($sql);
                $user = $data['name'];
                $sql = "insert into sys_right (userid,user) values('".$userid[0]['id']."','$user')";
                $Model -> execute($sql);
				echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			}else{
				echo "<script>alert('添加失败！');window.parent.document.location.reload();window.parent.g_pop.close(); </script>";

			}
		}
    }

//Jquery.validate验证用户名是否存在
    public function checkusername() {
    	$user = M("sys_user");
        $getname = trim($_REQUEST['name']);
    	$data = $user->where("name='".$getname."'")->count();
		if($data>0){
    		die(json_encode(false));
    	}else{
    		die(json_encode(true));
    	}
    }

//Jquery.validate验证邮箱是否存在
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

    public function edituser() {
        $id=$_GET['id'];
        $user = M('sys_user');
        $data=$user->getByid($id);
        //$data = $user->where("id='".$id."'")->select();
        $this->assign('data',$data);
        $this->display('edituser');
    }

    public function saveedituser() {
        $id=$_POST['id'];
        $name=trim($_POST['name']);
        $email=trim($_POST['email']);
        $psw2=trim($_POST['psw2']);
        $psw=trim($_POST['psw']);
        $pwd=($_POST['pwd']);
        //var_dump($psw);echo "<br>";
        //var_dump($pwd);die;
        if($psw2 == $psw) {
            if($pwd == $psw){
                $sql="update sys_user set `name`='".$name."',`email`='".$email."',`psw`='".$pwd."' where id=".$id;
            }else{
                $psw=md5($psw);
                $sql="update sys_user set `name`='".$name."',`email`='".$email."',`psw`='".$psw."' where id=".$id;
            }
            $sql2="update sys_right set user='".$name."'where userid='".$id."'";
            $Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
            $data = $Model->execute($sql);
            $Model->execute($sql2);;
             echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";

        }else {
             echo "<script>alert('两次密码输入不一致！')window.parent.document.location.reload();window.parent.g_pop.close();</script>";
        }
    }

//Jquery.validate验证编辑之外的用户名是否存在
    public function checkeditname() {
        $id=$_GET['id'];
        //var_dump($id);die;
    	$user = M("sys_user");
        $getname = trim($_REQUEST['name']);
    	$data = $user->where("name='".$getname."'&& id !='".$id."'")->count();
		if($data>0){
    		die(json_encode(false));
    	}else{
    		die(json_encode(true));
    	}
    }

//Jquery.validate验证编辑之外的邮箱是否存在
    public function checkeditemail() {
        $id=$_GET['id'];
        $user = M("sys_user");
        $getnemail = trim($_REQUEST['email']);
    	$data = $user->where("email='".$getnemail."'&& id !='".$id."'")->count();
		if($data>0){
    		die(json_encode(false));
    	}else{
    		die(json_encode(true));
    	}
    }
    public function updateUser() {
        $id=$_POST['id'];
        $name=trim($_POST['name']);
        $email=trim($_POST['email']);
        $psw2=trim($_POST['psw2']);
        $psw=trim($_POST['psw']);
        $pwd=($_POST['pwd']);
		if($pwd == $psw){
			$sql="update sys_user set `name`='".$name."',`email`='".$email."',`psw`='".$pwd."' where id=".$id;
		}else{
			$psw=md5($psw);
			$sql="update sys_user set `name`='".$name."',`email`='".$email."',`psw`='".$psw."' where id=".$id;
		}
		$sql2="update sys_right set user='".$name."'where userid='".$id."'";
		$Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
		$data = $Model->execute($sql);
		$rec=$Model->execute($sql2);;
		echo $rec;
    }	
	
    public function saveUser() {
		$err="";
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$m=M("sys_user");
		$cond['name']=$name;
		$data=$m->where("name='".$name."' or email='".$email."'")->count();
		if ($data>0)
			$err="名称或Email重复(Name or Email existed)!";
		else
		{
			$sql="insert into sys_user(name,psw,email) values ('".trim($_POST['name']) ."','". md5(trim($_POST['psw'])) ."','". trim($_POST['email']) ."')";
			$m=new Model();
			$data=$m->execute($sql);
			
			if($data==0)
			{
				$err="添加失败！";
			}
			else
			{
					$Model = new Model();
					$sql = "select id from sys_user order by id desc limit 1";
					$userid=mysql_insert_id();
					$user = $data['name'];
					$sql = "insert into sys_right (userid,user) values('".$userid."','".$name."')";
					$data = $Model -> execute($sql);
			}
		}
		echo $err;
    }
    //修改密码
    public function update()
    {
        $name=$_POST['user'];
        $pwd=$_POST['oldpwd'];
        $newpwd=$_POST['newpwd'];
        $m = New Model();
        $sql = '';
    }
	public function list_role(){
		System::openModel("sys_role");
	}
	
    public function getUsersrole(){
	    $cus = M("sys_role");
        $data = $cus->where("SYS_ORGID=".System::orgid())->select();
        echo json_encode($data);
    }	
   public function getFuncsOfRole(){
		$cus = M("sys_right");
        $rolename = $_REQUEST["rolename"];
        $data = $cus->field('role,functions,formedit')->where("role='".$rolename."'")->select();
		echo json_encode($data);
    }	
	public function get_access_base()
	{
		echo C("mcss_access_base");
	}
	public function shortcut()
	{
		$appname=$_SESSION['ORGCODE'];
		$this->assign('mcss_app',$appname);
		$this->display();	
	}	
	
	public function getMenus()
	{
		//echo 12;exit;
		$name = $_SESSION['loginuser'];
 		//将取出的权限赋值给$menus
		if($name == 'admin') {
 			$sql = "select no,name,groupno,groupname from sys_function where status='visible'";
			
			$app=$_SESSION["ORGCODE"];
				
			if (isset($app))
				$sql.=" and (apps like '%".$app."%' or apps like '%[ALL]%' )";
			$sql.=" order by sort ";
			//Log::write($sql);
			$m=new Model();
			$menus =$m->query($sql);
		
		}
		else {
			$menus =System::get_loginuser_right();
		 }
 
		echo json_encode($menus); 
	}
	//获得指定角色的扩展属性
	function checkRoleAccess()
	{
		$code=System::loginuserrole();
		echo Tool::get1bysql("select extension from sys_role where code='$code'");
	}	
	//根据登录名获得用户的角色ID
	function getCharacter(){
		import("@.ORG.System");
		$user = System::loginuser();
		$m = new Model();
		$character = $m->query("select roleid from sys_user where name = '$user'");
		echo $character[0]['roleid'];
	}
	//根据登录名获得用户的角色名称
	function getCharacterName(){
		import("@.ORG.System");
		$user = System::loginuser();
		$m = new Model();
		$character = $m->query("select code from sys_role where id=(select roleid from sys_user where name = '$user')");
		echo $character[0]['code'];
	}
	
	function getUserAndRoleListOfFunction()
	{
		$func_code=$_REQUEST["func_code"];
		$func_id=Data::sql1("select id from sys_function where no='$func_code'");
		$sql="SELECT user,(select name from sys_role where code=a.role  limit 0,1)  as rolename FROM sys_right a  WHERE functionids like '%$func_id,%'";
		$rows=Data::getRows($sql);
		$users='';
		$roles='';
		foreach($rows as $row)
		{
			if ($row['user'])
			{
				if ($users)
					$users.=",";
				$users.=$row['user'];
			}
			if ($row['rolename'])
			{
				if ($roles)
					$roles.=",";
				$roles.=$row['rolename'];
			}
		}
		echo "用户：$users;角色：$roles";
	}
	function exectesql(){
		$m = new Model();
		$sql = $_REQUEST['sql'];
		System::log($sql);
		$sql= str_replace("\\","",$sql);
		$result = $m->execute($sql);
		$error = $m->getDbError();
		echo $error.'|'.$result;
	}
	//移除服务器上的文件
	function removeFile(){
		$ids = $_REQUEST['ids'];
		$m = new Model();
		$arr = explode(',',$ids);
		for($i = 0;$i < count($arr);$i++){
			$id = $arr[$i];
			$sql = "select filepath from sys_file where id = $id";
			$filepath = Tool::get1bysql($sql);
			if($filepaths)
				$filepaths.=',';
			$filepaths.=$filepath;
		}
		$arr = explode(',',$filepaths);
			//	System::log($filepaths);
		for($i = 0;$i < count($arr);$i++){
			$filepath = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile/'.$arr[$i];
			if(file_exists($filepath)){
				unlink($filepath);
			}
		}
	}
}
?>