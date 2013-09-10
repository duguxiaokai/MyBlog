<?php
class IndexAction extends Action{
	
    function index() {
    	if (System::loginuser())
    	{
    		$n=Data::sql1("select count(*) from oa_project where status<>'' and SYS_ADDUSER='".System::loginuser()."'");
    		if ($n>0)
				$this->redirect("Project/projectdetail");    	
			else
				$this->redirect("Project/newProject");    	
		}
		else
			$this->redirect("Project/newProject");    	
    }
	
    function index3() {
		if (!$_SESSION['loginuser'] || $_SESSION['loginuser']==null)
		{
    		$this->display('login');
			return;
		}
		$a=$_SESSION["loginuser_funcs_arr"];
		$todayinfo=$this->getTodayPerson();
		$this->assign('zuizao',$todayinfo["zuizao"]);
		$this->assign('zuizao_time',$todayinfo["zuizao_time"]);
		$this->assign('zuichang',$todayinfo["zuichang"]);
		$this->assign('duojiu',$todayinfo["duojiu"]);
		$this->assign('shouxing',$todayinfo["shouxing"]);
		
		$daywork=$this->getUserUnfinishperDayWork();
		
		$this->assign('daywork',$daywork["unfdaywork"]);
		$this->assign('bizopportunity',$daywork["bizopportunity"]);
		$this->assign('myproject',$daywork["myproject"]);
		$this->assign('message',Tool::get1bysql("select count(id) from sys_message where reciever_id = ".Organization::MyStaffID()." and statue = 0 and SYS_ORGID = ".System::orgid()));
		
		$this->assign('dataname',$a);
		$appname=$_SESSION["ORGCODE"];
		$this->assign('mcss_app',$appname);
    	$this->display('index');
    }
    	
    function index1() {
		if (!$_SESSION['loginuser'] || $_SESSION['loginuser']==null)
		{
    		$this->display('login');
			return;
		}
		$appname=$_SESSION["ORGCODE"];
		$this->assign('mcss_app',$appname);
		$this->assign('mcss_theme',$mcss_theme);
    	$this->display();
    }

	function index2() {
		if (!$_SESSION['loginuser'] || $_SESSION['loginuser']==null)
		{
    		$this->display('login');
			return;
		}
		$appname=$_SESSION["ORGCODE"];
		$this->assign('mcss_app',$appname);
		$this->assign('mcss_theme',$mcss_theme);
    	$this->display();
    }
    public function getTodayPerson()
	{
		
		//最早来的人
		$sql = "select staffid,min(begintime) as begintime from oa_attendance where markdate=CURDATE() and SYS_ORGID=".System::orgid();
		$m=new Model();
		$rows = $m->query($sql);
		$staffid = $rows[0]['staffid'];
		$sql1 = "select name from sys_staff where id = '".$staffid."'";
		$name = Data::sql1($sql1);
		$result["zuizao"]=$name;
		$result["zuizao_time"]=$rows[0]['begintime'];
		
		//工作时间最长的人
		$sql2 = "select staffid,max(timestampdiff(hour,begintime,endtime)) from oa_attendance where markdate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) and SYS_ORGID=".System::orgid();
		$staffid1 = Data::sql1($sql2);
		$sql3 = "select name from sys_staff where id = '".$staffid1."' and SYS_ORGID=".System::orgid();
		$name1 = Data::sql1($sql3);
		if($name1)
		{
			$result["zuichang"]=$name1;
		}
		else
		{
			$result["zuichang"]="无";
		}
		
		//我今天工作多久
		$sql4 = "select timestampdiff(hour,begintime,'now()') as workhour from oa_attendance where markdate=CURDATE() and staffid=Me() and SYS_ORGID=".System::orgid();
		$sql4=Expression::parseExpression($sql4);
		$workhour = Data::sql1($sql4);
		if($workhour!='')
			$result["duojiu"]=$workhour."h";
		else
			$result["duojiu"]='';
		return $result;
		
	}	
	function getBirthday()
	{
		//本月寿星
		$sql5 = "select name,DATE_FORMAT(birthday,'%m-%d') as birthday from sys_staff where DATE_FORMAT(birthday,'%m-%d')=DATE_FORMAT(now(),'%y-%m')";
		System::log($sql5);
		echo Data::json($sql5);
	}
	
	//判断是否第一次登录，弹出打卡
	function needDaka()
	{
		$need=1;
		$staffid=Organization::Me();
		if ($staffid>0)
		{
			$sql = "select id from oa_attendance where markdate=CURDATE() and staffid=".$staffid." limit 0,1";
			$id = Data::sql1($sql);
			if ($id>0)
				$need=0;
		}
		else
			$need=0;
		echo $need;
	}
	function savemotto()
	{
		$motto = $_REQUEST['motto'];
		$id = $_REQUEST['id'];
		$sql = "update sys_staff set motto='".$motto."' where id=$id";
		$Model = new Model();
		$data=$Model->execute($sql);
	}
	
	function delmotto()
	{
		$id = $_REQUEST['id'];
		$sql = "update sys_staff set motto='' where id=$id";
		$Model = new Model();
		$data=$Model->execute($sql);
	}
	
	//获取回复列表
	function getReplyList(){
		$oid = $_REQUEST['oid'];
		$m = new Model();
		$sql = "select * from oa_info_reply where oid = ".$oid." order by SYS_ADDTIME asc";
		$data = $m->query($sql);
		for($i = 0;$i < count($data);$i++){
			$name = $data[$i]['SYS_ADDUSER'];
			$sql = "select photos from sys_staff where name = '$name' and SYS_ORGID = 17";
			$result = $m->query($sql);
			$data[$i]['imgsrc'] = $result[0]['photos'];
		}
		echo json_encode($data);
	}
	
	//根据作者名称得出发帖量以及作者头像
	function getautorinfo(){
		$autor = $_REQUEST['autor'];
		$sql = "select count(id) from oa_info where SYS_ADDUSER = '$autor'";
		$data = array();
		$data['articalsum'] = Tool::get1bysql($sql);
		$sql = "select photos from sys_staff where username = '$autor'";
		$data['imgsrc'] = Tool::get1bysql($sql);
		echo json_encode($data);
	}
	
	//通过username获取角色id
	function getroleid()
	{
		$username = $_REQUEST['username'];
		$sql = "select roleid from sys_user where name = '".$username."'";
		$m = new Model();
		$result = $m->query($sql);
		echo $result[0]['roleid'];
	}
	
	//设置页面变量
	function viewRecord(){
		$id = $_REQUEST['id'];
		$m = new Model();
		$sql = "select *,count(id) as countartical from oa_info where id = $id";
		$result = $m->query($sql);
		if(!$result[0]['count'])
			$result[0]['count'] = 0;
		$this->assign('count',$result[0]['count']);
		$sql = "select count(id) from oa_info_reply where oid = $id";
		$countreply = Tool::get1bysql($sql);
		if(!$countreply)
			$countreply = 0;
		$this->assign('countreply',$countreply);
		$this->display();
		
	}
	//赞同
	function makeyes(){
		$id = $_REQUEST['id'];
		$m = new Model();
		$sql = "select yes from oa_info_reply where id = $id";
		$result = $m->query($sql);
		$data = $result[0]['yes'] + 1;
		$sql = "update oa_info_reply set yes = $data where id = $id";
		$m->execute($sql);
		echo $data;
	}
	//不赞同
	function makeno(){
		$id = $_REQUEST['id'];
		$m = new Model();
		$sql = "select no from oa_info_reply where id = $id";
		$result = $m->query($sql);
		$data = $result[0]['no'] + 1;
		$sql = "update oa_info_reply set no = $data where id = $id";
		$m->execute($sql);
		echo $data;
	}
	//通过username获取员工id
	function getstaffid()
	{
		$id= $_REQUEST['id'];
		$sql="SELECT id FROM sys_staff WHERE username =(select name from sys_user where id=$id)";
		$m = new Model();
		$result = $m->query($sql);
		System::log($result[0]['id']);
		echo $result[0]['id'];
	}
	//根据username获得员工头像
	function getStaffImg()
	{
		$user=$_SESSION['loginuser'];
		$sql = "select photos from sys_staff where username='$user'";
		echo Data::sql1($sql);
	}
	//根据用户名得到用户的心情
	function getLoginUserPlan()
	{
		$user=$_SESSION['loginuser'];
		$sql="SELECT plan FROM `oa_attendance` where staffid=(SELECT id FROM sys_staff where username='$user') order by id desc limit 1";
		$m = new Model();
		$result = $m->query($sql);
		echo $result[0]['plan'];
	}
	//得到用户今日未完成的日工作
	function getUserUnfinishperDayWork()
	{
		$orgid=$_SESSION["ORGID"];
		$user=$_SESSION['loginuser'];	
		$data=array();
		//日工作（执行人我，今天的，未完成，日报,本组织的）
		$sql="SELECT count(id) as num FROM `oa_task` where tag=2 and cat='日报' and finishper=0 and begindate=CURDATE()and enddate=CURDATE() and executerid=(SELECT id FROM sys_staff where username='$user') and SYS_ORGID=$orgid";
		System::log("得到用户今日未完成的日工作".$sql);
		$m = new Model();
		$result = $m->query($sql);	
		$data["unfdaywork"]=$result[0]["num"];
		//商机（拥有者我，未完成，本组织的）
		//$sql1="SELECT count(*)as num1 FROM biz_opportunity where ownerid = (SELECT id FROM sys_staff where username='$user') and status='未完成' and SYS_ORGID = '$orgid'";
		$sql1="select count(*) as num1 from (SELECT a.id,a.no,a.status,a.ownerid,a.SYS_ADDUSER,a.source, a.value, a.reqtype, a.stage, b.name, c.name AS contactname, c.tel, (select title from biz_opportunity_follow where bid = a.id order by SYS_ADDTIME desc limit 1) as title,(select content from biz_opportunity_follow where bid = a.id order by SYS_ADDTIME desc limit 1) as content,(select nextfllowtime from biz_opportunity_follow where bid = a.id order by SYS_ADDTIME desc limit 1) as nextfllowtime FROM biz_opportunity AS a LEFT JOIN biz_customer AS b ON a.custid = b.id LEFT JOIN biz_contact AS c ON b.id= c.custid where a.SYS_ORGID = ".System::orgid()." and c.id = (select max(id) from biz_contact where custid=b.id) and a.id in (select bid from biz_opportunity_follow where nextfllowtime='".Expression::today()."')) a";
		//Expression::today()()."')";
		$result = $m->query($sql1);	
		System::log("韩丽芳".$sql1);
		$data["bizopportunity"]=$result[0]["num1"];
		
		//项目（执行人我，本组织的）
		$sql2="select count(*) as num2 from oa_project where executerid= (SELECT id FROM sys_staff where username='$user') and SYS_ORGID = '$orgid'";// and finishper<>1";
		$result = $m->query($sql2);	
		System::log("得到用户未完成的项目".$sql2);
		$data["myproject"]=$result[0]["num2"];
		return $data;
	}
	function getDayAndWeekTotal(){
		$weekfilter = str_replace("\\","",$_REQUEST["weekfilter"]);
		$weekfilter = str_replace("<yh>","'",$weekfilter);
		$dayfilter = str_replace("\\","",$_REQUEST["dayfilter"]);
		$sql = "select count(id) from oa_task where $weekfilter";
		$weektotal = Tool::get1bysql($sql);
		System::log($sql);
		$sql = "select count(id) from oa_task where $dayfilter";
		$daytotal = Tool::get1bysql($sql);
		echo $weektotal."|".$daytotal;
	}
	//获取论坛文章内容
	function getRowsInfo(){
		$param = $_REQUEST['param'];
		$id = $_REQUEST['id'];
		$orgid = System::orgid();
		$m = new Model();
		if($param=='next')
			$sql = "select * from oa_info where id > $id and SYS_ORGID = $orgid order by id asc limit 1";
		else
			$sql = "select * from oa_info where id < $id and SYS_ORGID = $orgid order by id desc limit 1";
		System::log($sql);
		echo json_encode($m->query($sql));
	}
	//根据组织ID得到公司LOGO
	 function getLogoByOrgid()
	 {
		$sql="SELECT `logo` FROM `sys_org` where id=".System::orgid();
		$m=new Model();
		$rows = $m->query($sql);
		System::log("**************".$rows[0]['logo']);
		echo $rows[0]['logo'];
	 }
	 //更新信息的状态
	 function updateMessageStatue(){
		$id = $_REQUEST["id"];
		$m = new Model();
		$sql = "update sys_message set statue = 1 where id = $id";
		$m->execute($sql);
	 }
	 function getAllMood(){
	 	$sql="SELECT staffname,begintime,plan,endtime,summary,(select photos from sys_staff where id=a.staffid) as pic FROM oa_attendance a where SYS_ORGID=".System::orgid()." order by begintime desc  limit 0,20";
		$m=new Model();
		$rows = $m->query($sql);
		$data["data"]=$rows;
		$data["name"]=System::loginusername();
		echo json_encode($data);
	 }
}
?>