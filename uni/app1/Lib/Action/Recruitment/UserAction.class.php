<?php
header('content-type:text/html;charset=utf-8;');
class UserAction extends Action{	
	function site(){
		$userid   = Data::sql1("select id from sys_user where name='".$_SESSION['loginuser']."'");
		$email    = Data::sql1("select email from sys_user where name='".$_SESSION['loginuser']."'");
		$uid      = Data::sql1("select id from recruitment_user where userid='".$userid."'");
		$sqlInfo  = "select * from recruitment_user where userid='".$userid."'";
		$userInfo = Data::getRows($sqlInfo);
		$this->assign('userInfo',$userInfo);
		$this->assign('email',$email);
		
		$sql = "select * from recruitment_resume where userid='".$uid."'";
		$resume = Data::getRows($sql);
		$this->assign('resume',$resume);
		
		$districtarr = array('0'=>'东城区','1'=>'西城区','2'=>'崇文区','3'=>'宣武区','4'=>'朝阳区','5'=>'海淀区',
							 '6'=>'丰台区','7'=>'石景山','8'=>'顺义区','9'=>'昌平区','10'=>'门头沟','11'=>'通州区',
							 '12'=>'房山区','13'=>'大兴区','14'=>'怀柔区','15'=>'平谷区','16'=>'延庆县','17'=>'密云县',);
		$this->assign('districtarr',$districtarr);
		$postarr = array('0'=>'商场导购','1'=>'超市促销员','2'=>'钟点工','3'=>'迎宾-接待','4'=>'配菜-打荷','5'=>'洗碗工',
						 '6'=>'餐饮-酒店管理','7'=>'救生员',);
		$this->assign('postarr',$postarr);
		
		$this->display();
	}
	//修改基本信息
	function updateInfo(){
		$m      = new Model();
		$userid = Data::sql1("select * from sys_user where name='".$_SESSION['loginuser']."'");
		$id     = Data::sql1("select * from recruitment_user where userid='".$userid."'");
		if (isset($_POST['submit'])&& $_POST['submit']){
			$w2       = $_FILES['imgfile']['name'];
			$tmp      = pathinfo($w2);
			$n2       = date("YmdHis",time()).'.'.$tmp['extension'];
			$savePath = "Public/uploadfile/".$n2;
			if($w2 == ''){
				echo "<script language='javascript'>alert('请选择图片');history.go(-1);</script>";
				exit;
			}
			//允许上传的文件格式
			$tp = array("image/gif","image/jpeg");
			//检查上传文件是否在允许上传的类型
			if(!in_array($_FILES["imgfile"]["type"],$tp))
			{
				echo "<script language='javascript'>alert('格式不对');history.go(-1);</script>";
				exit;
			}
			if(($_FILES["imgfile"]["size"]/1024) > 1000){
				echo "<script language='javascript'>alert('文件太大');history.go(-1);</script>";
				exit;
			}
			if(is_uploaded_file($_FILES['imgfile']['tmp_name'])) {
				if(move_uploaded_file($_FILES['imgfile']['tmp_name'],$savePath))
					$sql    = "update recruitment_user set photo='".$n2."' where id=".$id;
					System::log($sql);
					$result = $m->execute($sql);
					if($result > 0){
						echo "<script language='javascript'>alert('上传成功');location.href = document.referrer;</script>";return;
					}
					else{
						echo "<script language='javascript'>alert('上传失败');history.go(-1)</script>";
					}
			}
		}else{
			$name   = $_REQUEST['name'];
			$sex    = $_REQUEST['sex'];
			$age    = $_REQUEST['age'];
			$height = $_REQUEST['height'];
			$phone  = $_REQUEST['phone'];
			$email  = $_REQUEST['email'];
			$sql1    = "update recruitment_user set name='".$name."',sex='".$sex."',age='".$age."',height='".$height."',phone='".$phone."' where id=".$id;
			$result1 = $m->execute($sql1);
			$sql2    = "update sys_user set email='".$email."' where id=".$userid;
			$result2 = $m->execute($sql2);
			System::log('liurui'.$sql1);
			System::log('liu'.$sql2);
			if($result1 == 1 || $result2 == 1)
				$result = 1;
			elseif($result1 == 0 || $result2 == 0)
				$result = 2;
			echo $result;
		}	
	}
	function upload(){
		if (isset($_POST['submit'])&& $_POST['submit']){
		$w2       = $_FILES['imgfile']['name'];
		$tmp      = pathinfo($w2);
		$n2       = date("YmdHis",time()).'.'.$tmp['extension'];
		$savePath = "D:/wamp/www/jusaas/Public/uploadfile/".$n2;
		//允许上传的文件格式
		$tp = array("image/gif","image/jpeg");
		//检查上传文件是否在允许上传的类型
		if(!in_array($_FILES["imgfile"]["type"],$tp))
		{
			echo "<script>alert('格式不对');history.go(-1);</script>";
			exit;
		}
		if(($_FILES["imgfile"]["size"]/1024) > 1000){
			echo "<script>alert('文件太大');history.go(-1);</script>";
			exit;
		}
		if(is_uploaded_file($_FILES['imgfile']['tmp_name'])) {
			if(move_uploaded_file($_FILES['imgfile']['tmp_name'],$savePath))
				echo "上传成功";	
		}}
	}
	//验证邮箱是否存在
	function checkEmail(){
		$email = $_REQUEST['email'];
		$sql   = "select count(*) from sys_user where email='".$email."' ";
		$num   = Tool::get1bysql($sql);
		System::log('liurui'.$sql.'and'.$num);
		echo $num;
	}
	//显示简历
	function showResume(){
		$m        = new Model();
		$userid   = Data::sql1("select id from sys_user where name='".$_SESSION['loginuser']."'");
		$uid      = Data::sql1("select id from recruitment_user where userid='".$userid."'");
		$sql      = "select * from recruitment_resume where userid = '".$uid."'";
		$data     = $m->query($sql);
		echo json_encode($data[0]);
	}
	//保存简历
	function saveResume(){
		$m        = new Model();
		$userid   = Data::sql1("select id from sys_user where name='".$_SESSION['loginuser']."'");
		$uid      = Data::sql1("select id from recruitment_user where userid='".$userid."'");
		$post     = $_REQUEST['post'];
		$pay      = $_REQUEST['pay'];
		$district = $_REQUEST['district'];
		$resume   = $_REQUEST['resume'];
		$sql   = "select count(*) from recruitment_resume where userid='".$uid."' ";
		$num   = Tool::get1bysql($sql);
		if($num == 0)
			$sql = "insert into recruitment_resume(post,pay,district,resume,userid) values('".$post."','".$pay."','".$district."','".$resume."','".$uid."')";
		else
			$sql = "update recruitment_resume set post = '".$post."',pay = '".$pay."',district = '".$district."',resume = '".$resume."',userid = '".$uid."'";
		$result = $m->execute($sql);
		System::log($sql.'and'.$result);
		echo $result;
	}
	//验证原始密码是否正确
	function checkPsw(){
		$psw = $_REQUEST['psw'];
		$pswval = md5($psw);
		$pswold   = Data::sql1("select psw from sys_user where name='".$_SESSION['loginuser']."'");
		if($pswval == $pswold){
			$result = 1;
		}	
		else{
			$result = 0;
		}		
		echo $result;
	}
	//修改密码
	function updatePsw(){
		$m = new Model();
		$psw = $_REQUEST['newpsw'];
		$newpsw = md5($psw);
		$sql = "update sys_user set psw = '".$newpsw."' where name='".$_SESSION['loginuser']."'";
		$result = $m->execute($sql);
		System::log($sql.'and'.$result);
		echo $result;
	}
}
?>