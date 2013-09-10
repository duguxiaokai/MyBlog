<?php
header('content-type:text/html;charset=utf-8;');
class IndexAction extends Action{
	function index(){
		$post     = $_REQUEST['post'];
		$district = $_REQUEST['district'];
		//职位筛选
		if($post && $district){
			$sql      = "select * from recruitment_jobs where post='".$post."' and district='".$district."' order by SYS_ADDTIME desc limit 0,14";
		}
		else if($post){
			$sql      = "select * from recruitment_jobs where post='".$post."' order by SYS_ADDTIME desc limit 0,14";
		}
		else if($district){
			$sql      = "select * from recruitment_jobs where district='".$district."' order by SYS_ADDTIME desc limit 0,14";
		}
		else{
			$sql      = "select * from recruitment_jobs order by SYS_ADDTIME desc limit 0,14";
		}
		$jobsList = Data::getRows($sql);
		$this->assign('jobsList',$jobsList);
		$districtarr = array('0'=>'东城区','1'=>'西城区','2'=>'崇文区','3'=>'宣武区','4'=>'朝阳区','5'=>'海淀区',
							 '6'=>'丰台区','7'=>'石景山','8'=>'顺义区','9'=>'昌平区','10'=>'门头沟','11'=>'通州区',
							 '12'=>'房山区','13'=>'大兴区','14'=>'怀柔区','15'=>'平谷区','16'=>'延庆县','17'=>'密云县',);
		$this->assign('districtarr',$districtarr);
		
		//公司动态
		$sql  = "select * from ems_news where dir=111 order by id desc limit 0,7";
		$news = Data::getRows($sql);
		$this->assign('news',$news);
		
		//首页应聘技巧
		$jiqiao = $this->jiqiao(7,113);
		$this->assign('jiqiao',$jiqiao);
		
		//合作品牌
		$pinpai = $this->jiqiao(1,114);
		$this->assign('pinpai',$pinpai);
		
		//活动现场
		$huodong = $this->jiqiao(6,119);
		$this->assign('huodong',$huodong);
		
		$this->display();
	}
	//公司动态、招聘技巧
	function news(){
		$type = $_REQUEST['type'];
		$newsid = $_REQUEST['newsid'];
		if($type == 'dongtai'){
			$dir = '111';
		}else if($type == 'jiqiao'){
			$dir = '113';
		}else if($type == 'contact'){
			$dir = '121';
		}else if($type == 'mianze'){
			$dir = '122';
		}
		//内容页
		if($newsid){
			$sql      = "select * from ems_news where dir = ".$dir." and id = '".$newsid."' ";
			$newsType = 'detail';
			$newsdetail = Data::getRows($sql);
		}else{
			$sql  = "select * from ems_news where dir = ".$dir." order by id desc";
			$newsType = 'list';
		}		
		/***分页 start***/
		$page=$_REQUEST['p'];
		if(empty($page)) {
			$page=1;
		}
		$count=5;
		$from=($page-1)*$count;
		$sql.= " limit $from,$count";
		System::log($sql);	
		$newscount=Data::sql1("select count(*) from ems_news where dir = ".$dir."");
		$p=new Page($newscount,$count);
		$this->assign("newspage",$p->show());
		/***分页 end***/
		
		$news = Data::getRows($sql);
		if($type == 'dongtai')
			$type = '公司动态';
		else if($type == 'jiqiao')
			$type = '应聘技巧';
		else if($type == 'contact')
			$type = '联系我们';
		else if($type == 'mianze')
			$type = '免责声明';
		$this->assign('type',$type);
		$this->assign('newsType',$newsType); //页面类型 列表页/内容页
		$this->assign('news',$news);  //列表页
		$this->assign('newsdetail',$newsdetail); //内容页
		
		//右侧应聘技巧
		$jiqiao = $this->jiqiao(11,113);
		$this->assign('jiqiao',$jiqiao);
		
		//合作品牌
		$pinpai = $this->jiqiao(4,114);
		$this->assign('pinpai',$pinpai);
		
		$this->display();
	}
	
	//应聘技巧列表
	function jiqiao($num,$dir){
		$sqljq  = "select * from ems_news where dir=".$dir." order by id desc limit 0,".$num."";
		$jiqiao = Data::getRows($sqljq);
		return $jiqiao;
	}
	
	//招聘信息
	function job(){
		$jobid    = $_REQUEST['jobid'];
		$post     = $_REQUEST['post'];
		$district = $_REQUEST['district'];
		$nowtime  = date('Y-m-d',time());
		//内容页
		if($jobid){
			$sql      = "select * from recruitment_jobs where id= '".$jobid."' ";
			System::log('liurui'.$sql);
			$jobsType = 'detail';
			$job = Data::getRows($sql);
			$this->assign('job',$job);
		}
		//职位筛选
		else if($post && $district){
			if($post != 'all' && $district != 'all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else if($post == 'all' && $district != 'all'){
				$sql      = "select * from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else if($district == 'all' && $post != 'all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' ");
			}else if($post == 'all' && $district == 'all'){
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}
			$jobsType = 'list';
		}
		else if($post){
			if($post!='all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."'");
			}else{
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}			
			$jobsType = 'list';
		}
		else if($district){
			if($district != 'all'){
				$sql      = "select * from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else{
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}			
			$jobsType = 'list';
		}
		else{
			$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' and deadline > '".$nowtime."' order by id desc";
			$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' and deadline > '".$nowtime."'");
			$jobsType = 'list';
			
		}
		/***分页 start***/
		$page=$_REQUEST['p'];
		if(empty($page)) {
			$page=1;
		}
		$count=10;
		$from=($page-1)*$count;
		$sql.= " limit $from,$count";
		System::log($sql);	
		$p=new Page($newscount,$count);
		$this->assign("newspage",$p->show());
		/***分页 end***/
		
		//列表页内容
		$jobsList = Data::getRows($sql);
		$this->assign('jobsList',$jobsList);

		//属性(区分列表页和内容页)
		$this->assign('jobsType',$jobsType);
		$districtarr = array('0'=>'东城区','1'=>'西城区','2'=>'崇文区','3'=>'宣武区','4'=>'朝阳区','5'=>'海淀区',
							 '6'=>'丰台区','7'=>'石景山','8'=>'顺义区','9'=>'昌平区','10'=>'门头沟','11'=>'通州区',
							 '12'=>'房山区','13'=>'大兴区','14'=>'怀柔区','15'=>'平谷区','16'=>'延庆县','17'=>'密云县',);
		$this->assign('districtarr',$districtarr);
		$postarr = array('0'=>'商场导购','1'=>'超市促销员','2'=>'钟点工','3'=>'迎宾-接待','4'=>'配菜-打荷','5'=>'洗碗工',
						 '6'=>'餐饮-酒店管理','7'=>'救生员',);
		$this->assign('postarr',$postarr);
		
		//登陆状态
		if($_SESSION['loginuser']==null)
			$loginstate = 0;
		else
			$loginstate = 1;
		$this->assign('loginstate',$loginstate);
		
		//右侧应聘技巧
		$jiqiao = $this->jiqiao(11,113);
		$this->assign('jiqiao',$jiqiao);
		
		//合作品牌
		$pinpai = $this->jiqiao(4,114);
		$this->assign('pinpai',$pinpai);
		
		$this->display();
	}
	
	//招聘信息
	function jobs(){
		$jobid    = $_REQUEST['jobid'];
		$post     = $_REQUEST['post'];
		$district = $_REQUEST['district'];
		$nowtime  = date('Y-m-d',time());
		//内容页
		if($jobid){
			$sql      = "select * from recruitment_jobs where id= '".$jobid."' and state!='已结束' and deadline > '".$nowtime."' ";
			System::log('liurui'.$sql);
			$jobsType = 'detail';
			$job = Data::getRows($sql);
			$this->assign('job',$job);
		}
		//职位筛选
		else if($post && $district){
			if($post != 'all' && $district != 'all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else if($post == 'all' && $district != 'all'){
				$sql      = "select * from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else if($district == 'all' && $post != 'all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' ");
			}else if($post == 'all' && $district == 'all'){
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}
			$jobsType = 'list';
		}
		else if($post){
			if($post!='all'){
				$sql      = "select * from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where post='".$post."' and state!='已结束' and deadline > '".$nowtime."'");
			}else{
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}			
			$jobsType = 'list';
		}
		else if($district){
			if($district != 'all'){
				$sql      = "select * from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where district='".$district."' and state!='已结束' and deadline > '".$nowtime."'");
			}else{
				$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
				$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			}			
			$jobsType = 'list';
		}
		else{
			$sql      = "select * from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."' order by id desc";
			$newscount=Data::sql1("select count(*) from recruitment_jobs where state!='已结束' and deadline > '".$nowtime."'");
			$jobsType = 'list';
			
		}
		/***分页 start***/
		$page=$_REQUEST['p'];
		if(empty($page)) {
			$page=1;
		}
		$count=10;
		$from=($page-1)*$count;
		$sql.= " limit $from,$count";
		System::log($sql);	
		$p=new Page($newscount,$count);
		$this->assign("newspage",$p->show());
		/***分页 end***/
		
		//列表页内容
		$jobsList = Data::getRows($sql);
		$this->assign('jobsList',$jobsList);

		//属性(区分列表页和内容页)
		$this->assign('jobsType',$jobsType);
		$districtarr = array('0'=>'东城区','1'=>'西城区','2'=>'崇文区','3'=>'宣武区','4'=>'朝阳区','5'=>'海淀区',
							 '6'=>'丰台区','7'=>'石景山','8'=>'顺义区','9'=>'昌平区','10'=>'门头沟','11'=>'通州区',
							 '12'=>'房山区','13'=>'大兴区','14'=>'怀柔区','15'=>'平谷区','16'=>'延庆县','17'=>'密云县',);
		$this->assign('districtarr',$districtarr);
		$postarr = array('0'=>'商场导购','1'=>'超市促销员','2'=>'钟点工','3'=>'迎宾-接待','4'=>'配菜-打荷','5'=>'洗碗工',
						 '6'=>'餐饮-酒店管理','7'=>'救生员',);
		$this->assign('postarr',$postarr);
		
		//登陆状态
		if($_SESSION['loginuser']==null)
			$loginstate = 0;
		else
			$loginstate = 1;
		$this->assign('loginstate',$loginstate);
		
		//右侧应聘技巧
		$jiqiao = $this->jiqiao(11,113);
		$this->assign('jiqiao',$jiqiao);
		
		//合作品牌
		$pinpai = $this->jiqiao(4,114);
		$this->assign('pinpai',$pinpai);
		
		$this->display();
	}
	
	//我要兼职(投递简历)
	function join(){
		$jobid  = $_REQUEST['jobid'];
		$id = Data::sql1("select id from sys_user where name='".$_SESSION['loginuser']."'");
		$userid = Data::sql1("select id from recruitment_user where userid='".$id."'");
		$phone = Data::sql1("select phone from recruitment_user where userid='".$id."'");
		$m      = new Model();
		$time   = date("Y-m-d",time());
		//查询该用户是否被录用
		$hire  = Tool::get1bysql("select count(*) from recruitment_staff where phone='".$phone."' ");
		if($hire > 0)
			$hire = '是';
		else
			$hire = '否';
		//查询之前该信息的简历投递人员记录
		$idold  = Tool::get1bysql("select count(*) from recruitment_joindate where jobid='".$jobid."' and userid = '".$userid."'");
		if($idold == 0){			
			$sql      = "insert into recruitment_joindate(date,jobid,post,userid,hire)values('".$time."',$jobid,$jobid,$userid,
			'".$hire."')";
			$result   = $m->execute($sql);
		}else{
			//已经投过简历
			$result = 2;
		}
		System::log('result'.$result);
		System::log('sql'.$sql);
		System::log('s'.$s);
		System::log('num'.$idold);
		echo $result;
	}
	//注册
	function doRegister(){
		$type  = $_REQUEST['type'];
		$username  = $_REQUEST['username'];
		$psw       = md5($_REQUEST['psw']);
		$name      = $_REQUEST['name'];
		$sex       = $_REQUEST['sex'];
		$phone     = $_REQUEST['phone'];
		$email     = $_REQUEST['email'];
		$address   = $_REQUEST['address'];
		$year      = $_REQUEST['year'];
		$birthdays = $_REQUEST['birthdays'];
		$height    = $_REQUEST['height'];
		$cards     = $_REQUEST['cards'];
		$nowyear   = date("Y");
		$now       = date("Y-m-d");
		$addtime   = date("Y-m-d H:i:s");
		$age       = $nowyear - $year;
		if($age == 0)
			$age = 1;
		$m       = new Model();
		$sql1    = "insert into sys_user(name,psw,orgids,email,SYS_ADDTIME)values('".$username."','".$psw."','28','".$email."',
		'".$addtime."')";
		$result1 = $m->execute($sql1);
		$userid  = mysql_insert_id();
		$sql2    = "insert into recruitment_user(name,sex,age,height,card,address,birthday,phone,type,post,userid,ischeck,joindate)values
		('".$name."','".$sex."','".$age."','".$height."','".$cards."','".$address."',
		 '".$birthdays."','".$phone."','".$type."','','".$userid."','0','".$now."')";
		$result2 = $m->execute($sql2);
		if($result1 == 1 && $result2 == 1){
			echo "<script language=javascript>
					alert('注册成功!');window.location.href='/index.php/Recruitment/Index/';
				 </script>";
		}
		else{
			echo "<script language=javascript>
					alert('注册失败!');history.go(-1);
				 </script>";
		}			
		System::log($sql1);
		System::log($sql2);
	}
	//验证用户名唯一性
	function checkUser(){
		$username = $_REQUEST['username'];
		$count = Tool::get1bysql("select count(*) from sys_user where name = '".$username."' ");
		echo $count;
	}
	//验证邮箱唯一性
	function checkEmail(){
		$email = $_REQUEST['email'];
		$count = Tool::get1bysql("select count(*) from sys_user where email = '".$email."' ");
		echo $count;
	}
}
?>