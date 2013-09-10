<?php
header("content-type:text/html; charset=UTF-8"); 

class ProjectAction extends Action{
	public function index() {
		$this->redirect("Project/newProject");    	
	
//		echo "没有钱弄这个页面:(";
		return;	
		$this->assign("theme",C("mcss_theme"));
		$this->display();
	}	
	function test()
	{
	    //unset($_SESSION);
		//session_destroy();
		echo System::loginuser();
		echo $_SESSION['loginuer'];
		exit;
		echo strtotime('2013-08-11');
		echo "<br />";
		echo date('Y-m-d',strtotime('2013-08-11')+(24*3600));
	}
	public function projectdetail() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$yes=MCSS_Access::sharekeyIsRight();
		if (!$yes)
		{
			$this->assign("sharekeycheck",'ERROR');
		}
		$this->display();
	}		
	public function projectdetail1() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}		
	public function gantetu() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}		
	public function addTask() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	public function staffworkload() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	

	public function staff() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	public function deletenote()
	{
		$id = $_REQUEST['id'];
		$sql = "delete from oa_note where id ='".$id."'";
		$Model=new Model();
		$data=$Model->execute($sql);
		echo $data;
	}
	public function note_task()
	{
		$id = $_REQUEST['id'];
		$sql = "select * from oa_note where id = '".$id."'";
		$m = new Model();
		$rows = $m ->query($sql);
		$sql1 = "insert into oa_task (detail,executer,begindate,enddate) values ('".$rows[0]['name']."','".$rows[0]['SYS_ADDUSER']."','".$rows[0]['SYS_ADDTIME']."','".$rows[0]['SYS_ADDTIME']."')";
		$data=$m->execute($sql1);
		echo $data;
	}

	
	public function getWorkData()
	{
		//echo 123;exit;
		$pid=$_REQUEST["projectid"];
		$sql="select * from oa_task where projectid=$pid";
		$order="begindate";
		if ($_REQUEST["orderby"])
			$order=$_REQUEST["orderby"];
		$sql.=" order by ".$order;
		System::log($sql);
		$m=new Model();
		$rows=$m->query($sql);
		echo json_encode($rows);
	}
	public function getTasktype()
	{
		$projectid = $_REQUEST['projectid'];
		$sql = "select tasktypes from oa_project where id='".$projectid."'";
		$m = new Model();
		$rows = $m->query($sql);
		echo $rows[0]['tasktypes'];

	}
	public function getprojectid()
	{
		$staffid = $_REQUEST['staffid'];
		$sql = "select projectid from oa_project_staff where staffid='".$staffid."'";
		$m = new Model();
		$rows = $m->query($sql);
		$s="";
		for($i=0;$i<count($rows);$i++)
		{	
			if ($rows[$i]['projectid'])
			{	
				if ($s)
				{
					$s.=',';
					
				}
				$s.=$rows[$i]['projectid'];
			}
		}
		echo $s;
	}
	
	//查询项目周期数据
	function getPerdiodDate()
	{
		$periodId=$_REQUEST["periodId"];
		$sql="select * from oa_pm_period where id=$periodId";
		echo Data::json($sql);
	}
	
	//根据输入的成本周报，统计后更新到项目表中
	function updateProjectCostByInputedData()
	{
		//$sql="select staffid,cost from cost";
		//$unitcost=Data::getRows($sql);
		$sql="select a.projectid,sum(IFNULL(b.cost,22) *hours) as cost from oa_pm_inputprojectcost a";
		$sql.=" left join oa_pm_staffcostsetting b on b.staffid=a.staffid";
		//$sql.=" where a.status='comfirmed'";//暂时不需要确认
		$sql.=" group by  a.projectid ";
		//echo $sql;exit;
		System::log($sql);
		
		$rows=Data::getRows($sql);
		$count=0;
		foreach($rows as $row)
		{
			$n=Data::sql("update oa_project set cost_salary=".$row['cost'].",SYS_EDITTIME=now() where id=".$row['projectid']);
		}
		$n=Data::sql("update oa_project set cost_total=cost_sales+cost_managent+cost_salary+cost_other,SYS_EDITTIME=now()");
		$n=Data::sql("update oa_project set cost_profit=cost_contractmoney-cost_total,cost_aftertax=cost_contractmoney-cost_total-cost_tax,SYS_EDITTIME=now()");
		if (is_numeric($n))
			$count+=$n;
		echo $count;
	}
	//根据项目id获取项目信息
	function getProjectInfo(){
		$id = $_REQUEST['id'];
		$m = new Model();
		$sql = "select * from oa_project where id = $id";
		$data = $m->query($sql);
		$sql = "select name from sys_staff where id = '".$data[0]['executerid']."'";
		$data[0]["executer"] = Tool::get1bysql($sql);
		echo json_encode($data);
	}
	//根据项目id获取项目结束时间
	function getEndDateByid(){
		$id = $_REQUEST['id'];
		$sql = "select enddate from oa_project where id = $id";
		echo Tool::get1bysql($sql);
	}
	//更新项目结束时间
	function updateEndDateByid(){
		$m = new Model();
		$id = $_REQUEST['id'];
		$time = $_REQUEST['time'];
		$sql = "udpate oa_project set enddate = '$time' where id = $id";
		$m->execute($sql);
	}
	//展示页面的值
	public function viewProjectReport(){
		$m = new Model();
		$id = $_REQUEST['id'];
		$sql = "select * from oa_project_report where id = $id";
		$data = $m->query($sql);
		$manager = $data[0]['manager'];$projectid = $data[0]['projectid'];$periodname = $data[0]['periodname'];
		$begindate = $data[0]['begindate'];$enddate = $data[0]['enddate'];$summary = $data[0]['summary'];
		$time = $data[0]["SYS_ADDTIME"];$projectenddate = $data[0]['projectenddate'];$finishper = $data[0]['finishper'];
		$sql = "select name from oa_project where id = $projectid";
		$data = $m->query($sql);
		$projectname=$data[0]['name']; 
		$sql = "select * from oa_project_report_result where parentid = $id";
		$resultArr = $m->query($sql);
		$sql = "select * from oa_project_report_problem where parentid = $id";
		$problemArr = $m->query($sql);
		$sql = "select * from oa_project_report_nextweek where parentid = $id";
		$nextweekArr = $m->query($sql);
		$this->assign("time",$time);
		$this->assign("finishper",$finishper);
		$this->assign("projectenddate",$projectenddate);
		$this->assign("manager",$manager);
		$this->assign("periodname",$periodname);
		$this->assign("begindate",$begindate);
		$this->assign("enddate",$enddate);
		$this->assign("summary",$summary);
		$this->assign("resultArr",$resultArr);
		$this->assign("projectname",$projectname);
		$this->assign("problemArr",$problemArr);
		$this->assign("nextweekArr",$nextweekArr);
		$this->display();	
	}
	//批量生成待付款表
	function updateProjectCost()
	{		
		$sql="select id,name,executerid,(select code from oa_project b where b.id=a.projectid ) as prcode,(select name from oa_project b where b.id=a.projectid ) as prname,price,worktime,totalprice from oa_task a where status='canpay' and id not in (select task_id from oa_project_cost) ";
		System::log("得到付款表要插入的数据".$sql);
		$m = new Model();
		$result=$m->query($sql);
		if($result[0]['id'])
		{
			$time = Expression::now();
			for($i=0;$i<count($result);$i++)
			{
				$sql1="insert into oa_project_cost(task_id,task_name,executerid,project_name,project_code, unit_price,amount,cost,paid_date,SYS_ADDTIME) values('".$result[$i]['id']."','".$result[$i]['name']."','".$result[$i]['executerid']."','".$result[$i]['prcode']."','".$result[$i]['prname']."','".$result[$i]['price']."','".$result[$i]['worktime']."','".$result[$i]['totalprice']."','','$time')";
				System::log("批量生成待付款表".$sql1);
				$da=$m->execute($sql1);		
			}
			echo $da;
		}else{
			echo 0;
		}
	}
	//根据任务得到相应的信息
	function getInfoByTaskId()
	{
		$name=$_REQUEST['name'];
		$sql="SELECT `executerid`, (select code from oa_project b where a.projectid=b.id) as prcode,(select name from oa_project c where a.projectid=c.id) as prname ,price,worktime,totalprice FROM `oa_task` a where a.id=$name";
		System::log("根据任务得到相应的信息".$sql);
		$m = new Model();		
		$rows=$m->query($sql);
		echo json_encode($rows);
	}
	//根据id寻找客户名称
	function getcustnamebyid(){
		$id=$_REQUEST["id"];
		$sql="select name from biz_customer where id = $id";
		echo Tool::get1bysql($sql);
	}
	//添加任务消息
	function addMessage(){
		$reciever_id = $_REQUEST["recieverid"];
		$content = $_REQUEST["content"];
		System::sendMessage("$source给您分配了任务:".$content,"$source给您分配了任务:".$content,$reciever_id);
	}
	function getProjectDiscussionReply()
	{
		$pid=$_REQUEST["project_id"];
		echo Data::getDataJSON("select * from oa_discussion where project_id=$pid and replyto_id>0");
	}
	//修改项目任务类型
	function updataTaskType()
	{
		$tasktype = $_REQUEST["tasktype"];
		$projectid = $_REQUEST["projectid"];
		$orgid=System::orgid();
		$sql="select tasktypes from oa_project where id='$projectid'";
		//System::log("查询项目任务类型".$sql);
		$m = new Model();		
		$rows=$m->query($sql);
		$str = $rows[0]['tasktypes'];
		$arrtasktypes=(explode(",",$str));
		for($i=0;$i<count($arrtasktypes);$i++)
		{
			if($arrtasktypes[$i]==$tasktype)
			{
				unset($arrtasktypes[$i]);  
			}			
		}
		$newtasktype=implode(',',$arrtasktypes);
		$this->updateTaskTaskType($tasktype,'',$projectid);
		
		$sql1="update oa_project set tasktypes='$newtasktype' where id='$projectid'";
		System::log("修改项目任务类型".$sql1);
		echo Data::sql($sql1);		
	}
	function updataProjectStatus()
	{
		$projectid = $_REQUEST["projectid"];
		$status = $_REQUEST["status"];
		if (!$status)
			$status='plan';
		$sql="update oa_project set status='$status' where id='$projectid'";
		System::log($sql);
		echo Data::sql($sql);
	}
	//添加新的任务类型
	function insertTaskType()
	{
		$tasktype = $_REQUEST["tasktype"];
		$projectid = $_REQUEST["projectid"];
		$orgid=System::orgid();
		$sql="select tasktypes from oa_project where id='$projectid'";
		//System::log("查询项目任务类型".$sql);
		$m = new Model();		
		$rows=$m->query($sql);
		$str = $rows[0]['tasktypes'];
		if(strstr($str, $tasktype)>0)
		{
			echo 2;
			exit;
		}	
		if($str)
			$str.=',';
		$newtasktype=$str.$tasktype;		
		$sql1="update oa_project set tasktypes='$newtasktype' where id='$projectid'";
		System::log("新增项目任务类型".$sql1);
		echo Data::sql($sql1);
	}
	//今日项目讨论数量
	function getNewDiscussionCount()
	{
		$pid=$_REQUEST['project_id'];
		echo Data::sql1("select count(*) from oa_discussion where project_id='$pid' and SYS_ADDTIME>CURDATE()");
	}
	//修改任务名称
	function updateTaskTypeName()
	{
		$newname = $_REQUEST["newname"];
		$prevname = $_REQUEST["prevname"];
		$projectid = $_REQUEST["projectid"];
		$orgid=System::orgid();
		$sql="select tasktypes from oa_project where id='$projectid' ";
		//System::log("查询项目任务类型".$sql);
		$m = new Model();		
		$rows=$m->query($sql);
		$str = $rows[0]['tasktypes'];
		//System::log($str);
		
		$table_change = array($prevname=>$newname);
		$r=strtr($str,$table_change);
		//System::log($r);
		$this->updateTaskTaskType($prevname,$newname,$projectid);
		$sql1="update oa_project set tasktypes='$r' where id='$projectid'";
		//System::log("修改项目任务类型".$sql1);
		echo Data::sql($sql1);
	}
	//更新指定项目的某种tasktype类型的任务类型
	function updateTaskTaskType($prevtasktype,$newtasktype,$projectid)
	{
		$sql="update oa_task set tasktype='$newtasktype' where projectid='$projectid' and tasktype='$prevtasktype'";
		System::log("更新指定项目的某种tasktype类型的任务类型".$sql);
		return Data::sql($sql);
	}
	function updateTaskTaskTypefun()
	{
		
		$prevtasktype=$_REQUEST["prevtasktype"];
		$newtasktype=$_REQUEST["newtasktype"];
		$projectid=$_REQUEST["projectid"];
		echo $this->updateTaskTaskType($prevtasktype,$newtasktype,$projectid);
	}
	//获取到本公司所有的员工
	function getAllStaff(){
		$orgid = System::orgid();
		$m = new Model();
		$sql = "select id,name from sys_staff where SYS_ORGID = $orgid";
		$result = $m->query($sql);
		echo json_encode($result);
		
	}
	//给选中的员工发送信息和邮件
	function sendMessageAndMail(){
		$ids = $_REQUEST["ids"];
		$taskid = $_REQUEST["taskid"];
		$projectid = $_REQUEST["projectid"];
		$url = $_REQUEST["openurl"];
		$content = $_REQUEST["content"];
		$m = new Model();
		$sql = "select name from oa_task where id = $taskid";
		$task = Tool::get1bysql($sql);
		$sql = "select name from oa_project where id = $projectid";
		$projectname = Tool::get1bysql($sql);
		$user = System::loginuser();
		$sql = "select name from sys_staff where username = '$user'";
		$username = Tool::get1bysql($sql);
		$messagetitle = $username."在项目“".$projectname."”的任务“".$task."”上发表了评论";
		System::sendMessage($messagetitle,$content,$url,$ids);
		$mailbody = "<b>".$messagetitle."</b><p>".$content."<p>"."[<a href='http://".$_SERVER['HTTP_HOST'].$url."'>点此查看</a>]<br>赢盘软件";
		$idArr = explode(',',$ids);
		for($i = 0;$i < count($idArr);$i++){
			$id = $idArr[$i];
			$sql = "select email from sys_staff where id = $id";
			$email = Tool::get1bysql($sql);
			System::sendSystemMail($email,"赢盘系统消息通知",$mailbody);
		}
	}
	
	//创建新的项目
	function newProject()
	{
		$name='Project-'.String::rand_string(1,"","");
		$id=$this->getNewId();
		$sql="insert into oa_project(id,name,SYS_ADDTIME,SYS_ADDUSER) value('$id','$name','".date("Y-m-d H:i:s")."','".System::loginuser()."')";
		//System::log($sql);
		Data::sql($sql);
		//$pid=mysql_insert_id();
		$this->redirect("Project/projectdetail/id/".$id."/tag/new");
	}
	
	//判断是否可以访问项目
	function ifAccessProject()
	{
		if (System::loginuser()=='admin')
		{
			echo 1;
			return;
		}	
		$projectid=$_REQUEST['projectid'];
		$rows=Data::getRows("select SYS_ADDUSER,tag from oa_project where id='$projectid'");
		if (count($rows)==0)
		{
			echo -1;
		}
		else
		{
			$adduser=$rows[0]['SYS_ADDUSER'];
			$tag=$rows[0]['tag'];
			//if ($adduser && $adduser!=System::loginuser() && $shareto!='PUBLIC' && strpos($shareto.',',System::loginuser().',')===false)
			if ($adduser==null || $adduser==System::loginuser())
				echo 1;
			else if (strpos($tag,'public')>-1)
				echo 2;
			else
				echo 0;
		}
		//System::log('--'.$tag);
	}
	
	//获取项目和任务概要信息
	function getProjectAndTaskInfo()
	{
		$projectid=$_REQUEST['projectid'];
		$project=Data::getRows("select * from oa_project where id='$projectid'");
		$taskcount=Data::sql1("select count(*) from oa_task where projectid='$projectid'");
		$discussions=Data::getRows("select task_id from oa_discussion where project_id='$projectid'");
		for($i=0;$i<count($discussions);$i++)
		{
			$discus[$i]=$discussions[$i]['task_id'];
		}
		//获得一批随机id用户新增的任务的id
		for($i=0;$i<100;$i++){
			$newids_for_newtask[$i]=System::newid2();
		}
		$data=array("project"=>$project,'taskcount'=>$taskcount,'tasks_withdiscussion'=>$discus,'newids_for_newtask'=>$newids_for_newtask);
		//$data=array("project"=>$project,'taskcount'=>$taskcount);
		echo json_encode($data);
	}
	
	//保存甘特图显示选项
	function saveGantetuOption()
	{
		$projectid=$_REQUEST['projectid'];
		$option=$_REQUEST['option'];
		$sql="update oa_project set show_options='$option' where id='$projectid'";
		Data::sql($sql);
	}	
	
	//保存语言选择
	function saveLanguage()
	{
		$projectid=$_REQUEST['projectid'];
		$language=$_REQUEST['language'];
		$sql="update oa_project set language='$language' where id='$projectid'";
		Data::sql($sql);
	}	
	function demo()
	{
		$this->redirect("Project/projectdetail/id/18507/sharekey/JUcmSwXo/tag/demo");
	}
	
	//保存是否显示已完成任务的选项
	function saveHideDoneTaskOption()
	{
		$projectid=$_REQUEST['projectid'];
		$showoption=$_REQUEST['showoption'];//显示所有任务，包括已完成的
		$options=Data::sql1("select show_options from oa_project where id='$projectid'");
		$options=str_replace(",[showdonetask]",'',$options);		
		$options=str_replace(",[hidedonetask]",'',$options);
		$options.=',['.$showoption.']';
		$sql="update oa_project set show_options='$options' where id='$projectid'";
		Data::sql($sql);
	}	
	
	//获得时间加随机数的id，如果作为id，会有一定的重复率。适合于要求不太高的情况。
	function getNewId()
	{
		return substr(time()."",5).rand(10000,99999);
	}

	//保存刚打开的项目的id到历史	
	function SaveRecentProject()
	{
		$projectid=$_REQUEST['projectid'];
		$loginuser=System::loginuser();
		Data::sql("delete from sys_recent where cookievalue='$projectid' and cookiekey='OPENPROJECT' and SYS_ADDUSER='$loginuser'");
		Data::sql("insert into sys_recent(cookiekey,cookievalue,SYS_ADDUSER) values('OPENPROJECT','$projectid','$loginuser')");
	}
	//导出甘特图
	public function exportGanTetu(){
		if (!$modelid)
			$modelid = $_REQUEST['modelid'];
		import('@.Action.System.ModelAction');
		$list= new ModelAction();
		$m=new Model();
		$model=$list->getGoodModel($modelid);
		$fields=$model['fields'];
		$i = 0;
		foreach($fields as $field)
		{
			if(!$field['isvisible'] || $field['isvisible']=='true')
				$display[$i] = true;
			else
				$display[$i] = false;
			$array[$field['id']]=$field['name'];
			$i++;
		}
		$data=$_REQUEST['data'];//前台直接把数据传来。注：目前只有统计结果的MCTable会有这种情况
		if (!$data || $data=='undefined')//如果前台直接把数据传来，就直接用它导出到excel，否则重新组织sql语句查询
		{
			$filterFromClient=($_REQUEST['filter']);//本来应该用解码函数rawurldecode 或urldecode，但用了反而不行，不知道为何
			$tablename = $model['tablename']; 
			//$modelfilter = $model['filter']; 
			if ($_REQUEST['sql'])
				$model_sql=$_REQUEST['sql']; 
			else
				$model_sql=$model['sql'];

			$keyfield=$model['keyfield']; 
			if ($keyfield=='' || $keyfield==null)
				$keyfield='id';	
			$orderby=$model['orderby'];
			if($orderby=="" && $keyfield=="id"){
				$orderby =$keyfield." desc";
			}
			$word = $_REQUEST['sosoword'];//简单搜索过滤词
		
			$filter="";//最终的sql过滤条件

			$filter=$list->getSqlFilter($modelid,$model,$word,$filterFromClient);	
			$sql=$list->getsql($modelid,$tablename,$model_sql,$filter,$orderby,"0,5000");
			$data=$m->query($sql);
		}else{
			$data=Data::mcssStrToArray($data);
		}
		
		//表格样式
		$style=array(
		header_bgd_color=>'0xF0F0F2',//表头背景色
		header_border_color=>'000000',//表头边框颜色
		header_isBold=>true,//表头字体是否加粗
		header_width=>'10',//表头表格宽度
		header_completedate_width=>'11',//表头完整日期宽度
		header_daydate_width=>'3',//表头日的宽度
		header_week_color=>'0xEEEEEE',//表头周末的背景色
		bgd_color_zero=>'0x969696',//完成率为0的背景色
		bgd_color_complete=>'0x2E56F3',//完成率为1的背景色
		bgd_color_doing=>'0x766DE2',//完成率为0的背景色
		field_status_col_width=>3,//中间状态栏的宽度
		tasktype_fontsize=>14,//任务分组字体大小
		field_taskname_width=>30,//任务字段宽度
		);
		
		$projectid = $data[0]['projectid'];
		$sql = "select * from oa_project where id = $projectid";
		$rows = Data::getRows($sql);
		$project_data = $rows[0];
		$model=$list->getGoodModel("oa_project_basic");
		$fields=$model['fields'];
		$i = 0;
		foreach($fields as $field)
		{
			if(!$field['isvisible'] || $field['isvisible']=='true'){
				$array2[$field['id']]=$field['name'];
				$fieldType[$field['id']]=$field['type'];
			}
			$i++;
		}
		Vendor("oaExp");//导入phpexcel
		$path=$_SERVER['DOCUMENT_ROOT'].__ROOT__;
		$filetype = $_REQUEST["filetype"];//获取导出文件的类型
		$seloption =$project_data['show_options'];// "type,name,executer";
		echo '导出数据成功!;'.exportExcel($data,$project_data,$array,$array2,$fieldType,$style,$display,$seloption,$path,$filetype);
	}
	
	//删除评论
	function deleteDiscussion()
	{
		$id=$_REQUEST['id'];
		echo Data::sql("delete from oa_discussion where id='$id'");
	}	
	//复制项目信息
	function copyProject(){
		$id = $_REQUEST['id'];
		$user = $_REQUEST['username'];
		
		$m = new Model();
		$sql = "select * from oa_project where id = $id";
		$data = $m->query($sql);
		$projectid = System::newid2();
		$today = Expression::today();
		foreach($data[0] as $key=>$value){
			if($fields)
				$fields.=',';
			$fields.=$key;
			if($key=='id')
				$value = $projectid;
			else if($key=='begindate' || $key=='enddate')
				$value = "";
			else if($key=='SYS_ADDUSER' || $key=='SYS_EDITUSER')
				$value = $user;
			else if($key=='SYS_ADDTIME' || $key=='SYS_EDITTIME')
				$value = $today;
			if($fieldsValue)
				$fieldsValue.=',';
			$fieldsValue.="'".$value."'";
		}
		$sql = 'insert into oa_project('.$fields.')values('.$fieldsValue.')';
		$m->execute($sql);//复制项目信息
		
		$sql = "select * from oa_task where projectid = $id";
		$data = $m->query($sql);
		$fields = "";
		for($i = 0;$i < count($data);$i++){
			$taskid = System::newid2();
			$fieldsValue = "";
			foreach($data[$i] as $key=>$value){
				if($key=='id')
					$value = $taskid;
				else if($key=='begindate' || $key=='enddate')
					$value = "";
				else if($key=='SYS_ADDUSER' || $key=='SYS_EDITUSER')
					$value = $user;
				else if($key=='SYS_ADDTIME' || $key=='SYS_EDITTIME')
					$value = $today;
				else if($key == 'projectid')
					$value = $projectid;
				if($fieldsValue)
					$fieldsValue.=',';
				$fieldsValue.="'".$value."'";
				if($i > 0)
					continue;
				if($fields)
					$fields.=',';
				$fields.=$key;
			}
			$sql = 'insert into oa_task('.$fields.')values('.$fieldsValue.')';
			$m->execute($sql);//复制任务信息
		}
	}
}
?>