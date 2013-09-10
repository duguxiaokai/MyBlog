<?php
/*
这个类是MCSS核心类，用于在后端解析MCSS模型，包括模型、列表和表单
作者：陈坤极、刘兆菊、独孤晓凯-.-
*/
header("content-type:text/html; charset=UTF-8"); 
import("@.MCSS.System");
import("@.MCSS.MCSS_Access");
import("@.MCSS.Organization");
import("@.MCSS.Expression");
import("@.MCSS.MCSSModel");

class ModelAction extends Action{
	
	public $errorslist = array();
	
    public function _initialize(){
        if (strpos(C("mcss_needlogin_models").",",MODULE_NAME.',')>-1)
        {
			import("@.Action.CommonAction");
	     	$check=new CommonAction();
    	 	$check->checkLogin();
		}
    }

    function index() {
        echo "欢迎使用MCSS模型解析类！";
    }
	
	 
	public function getFileArray(array $_files, $top = TRUE)
	{
		$files = array();
		foreach($_files as $name=>$file){
			if($top) $sub_name = $file['name'];
			else    $sub_name = $name;
			
			if(is_array($sub_name)){
				foreach(array_keys($sub_name) as $key){
					$files[$name][$key] = array(
						'name'     => $file['name'][$key],
						'type'     => $file['type'][$key],
						'tmp_name' => $file['tmp_name'][$key],
						'error'    => $file['error'][$key],
						'size'     => $file['size'][$key],
					);
					$files[$name] = $this->getFileArray($files[$name], FALSE);
				}
			}else{
				$files[$name] = $file;
			}
		}
		return $files;
	}	  
	
	//文件上传处理
    public function uploadFile($files,$modelid,$fieldid,$recordid) 
    {
		//获得文件扩展名
		function getFileExt($file_name)
		{
			$arr =explode("." , $file_name); 
			if (count($arr)>1)
			{
				return $arr[count($arr)-1];
			}
			return ''; 
		}
		
		$files=$this->getFileArray($files);
		$savenames='';
		$errfile='';
		$saveNames='';
		$final_file_path= C("mcss_uploadpath");
		if (!$final_file_path)
		{
			$final_file_path=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile';
 		}
		$fields='';
		$fieldValues='';
		$errfile='';
		$maxmb=2;
		if (!is_null(C("mcss_max_filesize")))
			$maxmb=C("mcss_max_filesize");
		$maxsize=$maxmb*1024*1024;
		
 		foreach($files as $k=>$item)
		{
  			foreach($item as $k1=>$item1)
			{	
				$size=(int)$item1['size'];
				//System::log($item1['name'].$size.':'.$maxsize);
				if ($size > $maxsize)
				{
					$errStr="文件太大(>".$maxmb."M)-".$item1['name'];
					System::log($errStr);
					$errfile.=$errStr;//$item1['name'];
				}
				else
				{
					$ext=getFileExt($item1['name']);
					$saveas =time().'_'.rand().'.'.$ext;//这个方法缺点是不能保留原来的文件名。优点是解决了中文文件名问题
					//$saveas =time().'_'.$item1['name'];
					//echo $item1['tmp_name']+"___".$final_file_path.'/'.$saveas;
					if (!move_uploaded_file($item1['tmp_name'], $final_file_path.'/'.$saveas))
					{
						if ($errfile!='')
							$errfile+=',';
						$errfile.=$item1['name'];
					}
					else
					{
						$m = new Model();
						$model = $this->getGoodModel($modelid);
						$modelid = $model['id'];
						$notes = $model['title'].'->'.$this->getFieldProp($model,$fieldid,'name');
						
						$user = System::loginuser();
						$orgid = System::orgid();
						$time = Expression::now();
						$file_name = $item1['name'];
						$sql = "select count(id) from sys_file where owner_id = '$recordid' and modelname = '$modelid'";
						System::log($sql);
						$result = Tool::get1bysql($sql);
						if($result > 0){
							$sql = "select max(version) from sys_file 
									where owner_id = '$recordid' and modelid = '$modelname'";
							$version = Tool::get1bysql($sql)+1;
						}else{
							$version = 1;
						}
						$sql = "insert into sys_file(name,filepath,field,version,modelid,owner_id,notes,SYS_ORGID,SYS_ADDUSER,SYS_ADDTIME)
						values('$file_name','$saveas','$fieldid','$version','$modelid','$recordid','$notes','$orgid','$user','$time')";
						System::log($sql);
						$m->execute($sql);
						if ($fieldValues!='')
							$fieldValues.='~|~';
						$fieldValues.="".$k1."<=>".$item1['name']."~".$saveas;//附件名称保存为"原始文件名~储存文件名"的格式，例如"我的客户.xls~1369382988_221520896.xls"		
					}
				}
			}
		}
		//echo 123;exit;
		if ($errfile!='')
			return  'err:'.$errfile;  
  		else
			return  'ok:'.$fieldValues;
 	
	}
	
	

	
    public function savenewRecord() 
    {
		//savenewRecord方法是比较早的，最好不用，用下面的saveRecord代替。
		$this->saveRecord();
	}  
	
	//MCSS核心方法之一。用户保存、更新自动表单的数据
    public function saveRecord() 
    {
	
		$modelid = $_REQUEST['modelid'];		
 		$tablename='';
		if (isset($_REQUEST['tablename']))
			$tablename=$_REQUEST['tablename'];
		if ($tablename=="")
		{
			if (isset($modelid))
			{
				$model=$this->getGoodModel($modelid);
				$tablename=$model['tablename'];
			}
		}

		if ($tablename=='' && isset($modelid))
			$tablename = $modelid;

		if (!$model)
			$model=$this->getGoodModel($modelid);
	
		$err=MCSS_Access::checkAccess($modelid,'string');
		if ($err){
			echo $err;
			exit;		
		}

		$keyfield=$model['keyfield'];
		if (!$keyfield)
			$keyfield='id';
		$fieldvalues=$_REQUEST['fieldvalues'];	
 		$fieldvalues=str_replace("<|>","~|~",$fieldvalues);	
		
		$fieldvalues.="~|~".$files;
		 
  		if (!isset($id))
			$id=$_REQUEST['id'];	
		if ($id && $id!="undefined")
		{
			echo $this->updateRecord($tablename,"",$fieldvalues,$_REQUEST['useajax'],$id,$modelid);
			return;
		}
 
 	    $fieldvalueArray = explode('~|~',$fieldvalues);
 	    $num = count($fieldvalueArray);
       
 	    $fieldnames="";
 	    $values="";
 		for($i=0;$i<$num;$i++){
	 	    $arr = explode('<=>',$fieldvalueArray[$i]);
	 	    if (count($arr)==2)
	 	    {
	 	    	if ($fieldnames != "")
	 	    	{
	 	    		$fieldnames .= ',';
	 	    		$values .= ',';
	 	    	}
				
				//特殊字段处理
				$type=$this->getFieldProp($model,$arr[0],'type');
				$arr[1]=$this->getSystemDefaultValue($type,$arr[1]);			
	    		$fieldnames .= $arr[0];
	    		$values .= "'".$arr[1]."'";
				if ($this->getFieldProp($model,$arr[0],'unique')){
					$unic=$this->returnFieldUniqueCheck($modelid,$arr[0],$arr[1],$id);
					if (!$unic){
						$fname=$this->getFieldProp($model,$arr[0],'name');
						echo "'".$fname."'字段值'".$arr[1]."'重复了，请修改!!";
						exit;
					}
				}
				
    		}
 	    }
 
	
		$sql="insert into ".$tablename." (" .$fieldnames.") values (".$values.")";

		//if (C("mcss_log_saveRecord")) System::addlog('新增数据:'.$model['title'].".SQL:".$sql);
 		System::log($sql);

		$newid=0;
		$count=0;
		try
		{
			/*
			if ($_REQUEST['beginTransaction']=='true')
			{
				System::log('beginTransaction');
				Data::sql("BEGIN");
			}
			*/
			$m=new Model();
			$count=Data::sql($sql);
			$err=mysql_error();

			/*
			if ($_REQUEST['commitTransaction']=='true')
			{
				if ($count>0)
				{
					Data::sql("COMMIT");		
				}
				else
				{
					Data::sql("ROOLBACK");
				}
			}
			*/
			
			if ($count>0)
			{
				$newid=mysql_insert_id();				
				//解析执行记录新增后要额外执行的sql语句。sql在模型中定义
				$sql_after_inserted=$model["sql_after_inserted"];
				if ($sql_after_inserted)
				{ 
					$this->sql_after_inserted($sql_after_inserted,$tablename,$keyfield,$newid);
				}
			}			
			
		}
		catch(Exception $e)
		{
			echo  $e->getMessage();
			exit;
		}
		$r='';
		if ($err)
			echo $err;
		else
		if($count>0){
			if ($_REQUEST['need_return_newid']) //是否有上传文件
				$r="newid:".$newid;
			else
			{
				$r="newid:".$newid;
			}
		}
		else{
			$r= "保存失败！";
		}
		echo $r;
	}

//给特殊字段按特定规则赋值
function getSystemDefaultValue($type,$v)
{
	if ($type=="password"){
		$v=md5($v);
	}
	else if ($type=="SYS_ADDUSER" || $type=="SYS_EDITUSER"){
		$v=System::loginuser();
	}
	else if ($type=="SYS_ADDTIME" || $type=="SYS_EDITTIME"){
		$v=date("Y-m-d H:i:s");
	}
	else if ($type=="SYS_ORGID"){
		$v=System::orgid();
	}
	return $v;
}

	//新增记录后的sql语句执行
	function sql_after_inserted($sql_after_inserted,$tablename,$keyfield,$newid)
	{
		$sql_after_inserted=Expression::parseExpression($sql_after_inserted);
		preg_match_all("/(record.\w+)/", $sql_after_inserted,$match);
		if (count($match[0])>0)
		{
			foreach($match[0] as $onematch)
			{
				//$onematch的值应该类似：{record.id},{record.name}等
				$arr=explode(".",$onematch);
				if (count($arr)==2 && $arr[0]=='record' && $arr[1])
				{
					$sql="select ".$arr[1]." from ".$tablename." where ".$keyfield."='".$newid."'";
					System::log($sql);
					$factvalue=Tool::get1bysql($sql);
					$sql_after_inserted=str_replace($onematch,$factvalue,$sql_after_inserted);
				}
			}
			System::log($sql_after_inserted);
			Tool::sql($sql_after_inserted);
		}
	}
	
	function test123()
	{
		echo '123';
	}
	
	//获得指定模型的字段的属性值
    public function getFieldProp($model,$fieldid,$propname)
	{
		$fields=$model["fields"];
		foreach($fields as $field)
		{
			if ($field["id"]==$fieldid)
			{
				return $field[$propname];
			}
		}
		return null;
	}
	
	//从字段列表中获得指定字段的编辑类型.该方法应该用getFieldProp代替。没必要用
    public function getFieldType($fields,$fieldid)
	{
		for($i=0;$i<count($fields);$i++)
		{
			if ($fields[$i]["id"]==$fieldid){
				return $fields[$i]["type"];
			}
		}
		return null;
	}
	

	//通用的更新记录方法，重要！
    public function updateRecord($tablename,$keyfield,$fieldvalues,$useajax,$id,$modelid) 
    {
 		if ($tablename==null)
			$tablename = $_REQUEST['tablename'];
			
		if ($keyfield==null || $keyfield=="")
			$keyfield = $_REQUEST['keyfield'];
 		$model=$this->getGoodModel($modelid);
 
		if ($keyfield=="") 
			$keyfield=$model['keyfield'];
		if ($keyfield=="" || !$keyfield)
			$keyfield="id";
		if ($fieldvalues==null)
			$fieldvalues = $_REQUEST['fieldvalues'];
		System::log("测试字段".$fieldvalues);
 
 	    $fieldvalueArray = explode('~|~',$fieldvalues);
 	    
		$num = count($fieldvalueArray);
 	    $fieldnames="";
 	    $values="";
 	    $updatefields="";
  	    for($i=0;$i<$num;$i++)
		{
	 	    $arr = explode('<=>',$fieldvalueArray[$i]);
			if (count($arr)!=2)
			{
				$arr = explode('=',$fieldvalueArray[$i]);
			}
			
	 	    if (count($arr)==2)
	 	    {
	    		if ($arr[0] == $keyfield)
	    		{
	    			//$id = $arr[1];
	    		}
				else
				{
					if ($updatefields != "")
					{
						$updatefields .= ',';
					}
				 	$fieldv=$arr[1];
					$type=$this->getFieldProp($model,$arr[0],'type');
					$fieldv=$this->getSystemDefaultValue($type,$fieldv);					
					
					if ($this->getFieldProp($model,$arr[0],'unique')){
						$unic=$this->returnFieldUniqueCheck($modelid,$arr[0],$fieldv,$id);
						if (!$unic){
							$fname=$this->getFieldProp($model,$arr[0],'name');
							echo "'".$fname."'字段值'".$arr[1]."'重复了，请修改！";
							exit;
						}
					}
					$updatefields .= $arr[0]."='".$fieldv."'";
					
				}
    		}
 	    }
		
		//处理公共字段，如更新者,更新时间字段
		$fields=$model["fields"];
		$num = count($fields);
  	    for($i=0;$i<$num;$i++)
		{
		
	 	   	$type=$this->getFieldProp($model,$fields[$i]["id"],'type');
			$fieldv="";
			if ($type=="SYS_EDITUSER"){
				$fieldv=System::loginuser();
			}
			else if ($type=="SYS_EDITTIME"){
				$fieldv=date("Y-m-d H:i:s");
			}
			
			
			if ($fieldv)
			{
				if ($updatefields != "")
				{
					$updatefields .= ',';
				}
				$updatefields .= $fields[$i]["id"]."='".$fieldv."'";
			}
		}
		
		$err='';
		if($updatefields!='')
		{
 			$sql="update ".$tablename." set " . $updatefields ." where ".$keyfield."='".$id."'";
			if (C("mcss_log_saveRecord"))	System::addlog('更新数据:'.$model['title'].".SQL:".$sql);
			
			if (C("mcss_log_sql"))	Log::write($sql);
			$m=new Model();
			$data=$m->execute($sql);
			$errmsg=mysql_error();
			if($data>0){

			}
			else{
				$err= "保存失败".$errmsg;
			}
 		}
		else
		{
			$err="";
		}
 		if ($useajax=='false')
		{
			$r=$err;
			if ($err=='')
				$r="保存成功！";  
			$r="<script>alert('".$r."');document.location.href=document.referrer;</script>";
		}
		else
			$r=$err;
 		echo $r;
	}
		
	public function test1()
	{
		echo $_SESSION['loginuser'];
		echo 123;
		if (!$_SESSION['loginuser'])
		{
			echo '请登录！';
		}		
		exit;
		
		$m=$this->getGoodModel("1");
		print_r($m);
		 
		//echo $_SESSION['mcss_app'];
		//System::sendSystemMail("chenkunji@qq.com",'123','content123');
		//	setcookie("mcss_loginuserrole", "Alex Porter");
		//		echo $_COOKIE["mcss_loginuserrole"];
		//exit;
		//echo 1;exit;
		//$v=Organization::MyDeptStaffs_df();
		//echo $v;
		 
		//Vendor("sms.Demo");
		//c::test989();
		//test88();
		
		//import("Com.nusoap.sms.MCSS_SMS");
		//MCSS_SMS::sendsms(array("13811493004"),'短信测试');
		 
	}



	//得到某个模型，没有解析
    function getModel($modelid) {
		return MCSSModel::getModel($modelid);
	}


	//得到某个模型，解析了其中的变量
    function getGoodModel($modelid) {
		//if (!isset($modelid))
		//	$modelid=$_REQUEST["modelid"];
		$m=$this->getModel($modelid);
		$base=$m["base"];
		if ($base!=null && $base !="")
		{
			$basemodel=$this->getModel($base);
			$m=$this->copyFromBaseModel($m,$basemodel);
		}
		$m=$this->setFieldDataForModel($m);
		return $m; 
	}
	
	//得到表的所有信息（可自定义编写）
    function getTableInfo($modelid,$getname) {
		
		$m=$this->getGoodModel($modelid);

		if($getname=="ALL") {
			$r=$m;
		}
		else if($getname=="fields") {
			$r=json_encode($m[$getname]);
		}
		else if($getname=="fieldsArray") {
					$r=$m["fields"];
		}
		else
		{
			$r=$m[$getname];
		}
		return $r;
    }

	//处理数据模型的base属性，即从基类复制基类的属性(子类已有的属性值不复制)
    function copyFromBaseModel($model,$basemodel)
    {
		$newt=$basemodel;//将父类模型复制于子类
		foreach($model as $key=>$value)
		{
			if (isset($model[$key]))//子类拥有的除字段列表外复制
			{
				if($key!="fields"){
					$newt[$key]=$model[$key];
				}
			}
		}
				if(!$model['wayofcopyfields'])//如果默认不考虑父类的字段，只使用子类的字段
				{
					if($key=="fields" && $model["fields"]){
						$newt["fields"]=$model["fields"];
					}
				}
				
				else	
				if($model['wayofcopyfields']=="parent_and_child" )//如果同时考虑父类和子类的字段
				{
					if (isset($model['fields']))
					{
						for($k=0;$k<count($model['fields']);$k++){
							$exists=false;
							$length=count($basemodel['fields']);  
							for($i=0;$i<$length;$i++){
								if($model['fields'][$k]['id']==$basemodel['fields'][$i]['id']){
									$exists=true;
									foreach($basemodel['fields'][$i] as $key=>$value){
										if(!$model['fields'][$k][$key])
											$model['fields'][$k][$key]=$basemodel['fields'][$i][$key];
									}
									$newt['fields'][$i] = $model['fields'][$k];
								}
							}
							if (!$exists)
								$newt['fields'][]=$model['fields'][$k];
						}
					}
				}
				else//如果只考虑子类与父类ID相同的字段
				{
					$newt["fields"]=$model["fields"];
					if (isset($model['fields']))
					{
						for($k=0;$k<count($model['fields']);$k++){
							$length=count($basemodel['fields']);  
							for($i=0;$i<$length;$i++){
								if($model['fields'][$k]['id']==$basemodel['fields'][$i]['id']){
									$exists=true;
									foreach($basemodel['fields'][$i] as $key=>$value){
										if(!$model['fields'][$k][$key])
											$model['fields'][$k][$key]=$basemodel['fields'][$i][$key];
									}
									$newt['fields'][$k] = $model['fields'][$k];
								}
							}
						}
					}
				}
		return $newt;
    }

	//根据记录id获得一条记录
	public function getOneRecord()
	{
		$table = $_REQUEST['table'];
		$id = $_REQUEST['id'];
		$keyfield = $_REQUEST['keyfield'];	

		$modelid = $_REQUEST['modelid'];
		$model=$this->getGoodModel($modelid);
		if (C("mcss_log_getonerecord"))	System::log('打开记录:'.$model['title'].".ID:".$id);
		
		if (!isset($keyfield) && isset($model['keyfield']))
			$keyfield=$model['keyfield'];
		
		if ($keyfield==null || $keyfield=="")
			$keyfield ='id';
		
		$sql=$model['sql'];
		$filter=$model['filter'];
	
		if ($id!=null)
		{		
			if (strlen($sql)>0)
			{ 
				$sql="select a.* from (".$sql.") a where a.".$keyfield."='".$id."'";
			}
			else
			{
				$sql="select * from ".$table." where ".$keyfield."='".$id."'";
				
			}
			if ($filter)
			{
				$sql.=" and (".$filter.")";
			}
			if (C("mcss_log_sql"))	System::log($modelid.":".$sql);
			
			$err=$this->checkRecordAccess($modelid,$sql,$keyfield,$id);
			
			$err=MCSS_Access::checkAccess($modelid);
			if ($err)
			{
				echo $err;exit;
			}
			$m=new Model();
			$rows=$m->query($sql);
			$err=mysql_error();
			if ($err)
			{
				System::log($err);
				$err=array(array('err'=>"查询数据错误",'errcode'=>'sqlerror'));
				$data = json_encode($err);			
			}
			else		
				$data = json_encode($rows);
		}
        echo $data;
	}

	public function checkRecordAccess($modelid,$sql,$keyfield,$recordid)
	{
		//检查角色对模型的权限
		$err=MCSS_Access::checkAccess($modelid);
		if ($err){
			echo $err;
			exit;		
		}
		
		// //检查角色对指定记录的权限
		// $sql="select ".$keyfield." from (".$sql.") where ".$keyfield."'='".$recordid."'";
		// $m=new Model();
		// $rows=$m->query();
		// if (count($rows)==0)
		// {
			// $errmsg='unaccessible:权限不足！';
			// $err=array(array('err'=>$errmsg));
			// $logstr='unaccessible:权限不足！详细信息：模型ID:'.$modelid;
			// $logstr.=',用户名:'.System::loginuser().',角色:'.System::loginuserrole();
			// System::addlog($logstr);
			// return json_encode($err);
		// }
		
		//能到这步表示没有错误
		return null;
	}	
	//获得模型的标题列表，很少用到
    public function showHeaderList($table)
	{
        $table = $_REQUEST['table'];
        if($table == null) {
            $table = $_REQUEST['modelid'];
        }
        echo $this->getTableInfo($table,"fields");
    }

	//获得总记录数返回给前台调用者
	public function getTotalRows()
	{
        $modelid = $_REQUEST['table'];
		$sql = $_REQUEST['sql'];
		if (!isset($tamodelidble))
			$modelid =$_REQUEST['modelid'];
		$model=$this->getGoodModel($modelid);
		$word = $_REQUEST['sosoword'];//简单搜索过滤词
		
		$filterFromClient=($_REQUEST['filter']);
        $tablename = $model['tablename']; 
		$model_sql=$model['sql']; 	
	
		$filter=$this->getSqlFilter($modelid,$model,$word,$filterFromClient);
		System::log('过滤总数条件'.$filter);
		if($sql && $sql!='undefined'){
			$sql="select count(*) from (".$sql.") a";
		}else{
			if ($model_sql && $model_sql!='undefined')
				$sql="select count(*) from (".$model_sql.") a";
			else
				$sql="select count(*) from $tablename";
		}
		
		if ($filter)
			$sql.=" where ".$filter;
		$sql=$this->dealSpecialChar($sql);			
		System::log($sql);
		//if (C("mcss_log_sql")) Log::write("总数：".$sql);
		echo Tool::get1bysql($sql);
    }
	//获得分组记录的总数返回给前台调用者
	public function getTotal()
	{
		$modelid = $_REQUEST['modelid'];
		$name = $_REQUEST['name'];
		$value = $_REQUEST['value'];
		
		$model=$this->getGoodModel($modelid);
		$tablename = $model['tablename']; 
		 
		$sql="select count($name) from $tablename where $name = '".$value."'"; 
		echo Tool::get1bysql($sql);
	}
	//获得分组记录的总和返回给前台调用者
	public function getSum()
	{
		$modelid = $_REQUEST['modelid'];
		$name = $_REQUEST['name'];
		$value = $_REQUEST['value'];
		$linename = $_REQUEST['linename'];
		$lineid = $_REQUEST['lineid'];
		$tdid = $_REQUEST['tdid'];
		
		$model=$this->getGoodModel($modelid);
		$tablename = $model['tablename']; 
		 
		$sql="select sum($lineid) from $tablename where $name = '".$value."'"; 
		System::Log("执行统计:".$sql);
		echo $tdid.','.'&nbsp;'.$linename.'总计:'.Tool::get1bysql($sql);
	}
	//获得分组记录的平均值返回给前台调用者
	public function getAdv()
	{
		$modelid = $_REQUEST['modelid'];
		$name = $_REQUEST['name'];
		$value = $_REQUEST['value'];
		
		$model=$this->getGoodModel($modelid);
		$tablename = $model['tablename']; 
		 
		$sql="select sum($name) from $tablename where $name = '".$value."'"; 
		$sum=Tool::get1bysql($sql);
		$sql="select count($name) from $tablename where $name = '".$value."'"; 
		$total=Tool::get1bysql($sql);
		$adv=$sum/$total;
		echo $adv;
	}
	
	//////////上面的方法整理好了，下面再找时间整理。ikiller 2012-06-23 凌晨4：21
	
	
//	public function getTitle($table,$title){
//		$table = $_REQUEST['table'];
//		$title = $this->getTableInfo($table,"title");
//		echo $data = json_encode($title);
//	}

	//获得指定模型的模型数据
	public function getModelData(){
		$modelid = $_REQUEST['modelid'];
		$err=MCSS_Access::checkAccess($modelid);
		if ($err){
			echo $err;
			exit;		
		}

		$model =$this->getGoodModel($modelid);

		if (C("mcss_log_openmodel"))	System::addlog('打开:'.$model['title']);

		echo $data = json_encode($model);
	}
	

	//解析各种表达式，解析时要知道解析器所在的类，方法，返回值类型，返回值是否多个值
	function parseExpression($exprestionStr)
	{
		return Expression::parseExpression($exprestionStr);

	}
	

	//解析模型的字段默认值和data属性的sql语句
	//输入参数：$m:MCSS数据对象模型
	//返回：把字段默认值函数替换为函数值后的模型
    public function setFieldDataForModel($m){
		
		$fields=$m["fields"];
		
		$sql=$m["sql"];
		//Log::write($sql);
		if($sql)
		{
			$m["sql"]=$this->parseExpression($sql);
        }
		$editcondition=$m["editcondition"];
		if($editcondition)
		{
			$m["editcondition"]=$this->parseExpression($editcondition);
        }
		$filter=$m["filter"];
		if($filter)
		{
			$m["filter"]=$this->parseExpression($filter);
        }
		
		$n=count($fields);
		for($i=0;$i<$n;$i++){
			$value=$fields[$i];
			if (strpos($value['data'],"option:")>-1) //选项模型解析。例子：data=>"options:newstype" 将解析为sql:select name from sys_item where type='newstype'
			{
				$orgid = System::orgid();
				$datavalue=explode(":",$value['data']);
				if (count($datavalue)==2)
					$value['data']="sql:select name from sys_item where type='".$datavalue[1]."' and SYS_ORGID=$orgid order by orderlist";
			}
			if ($value['type']=="checkbox")
			{
				$m["fields"][$i]['data']="1:是,0:否";
			}			

			//下面解析data属性。如果data以“sql:”开始，则需要解析.Sql语句返回记录数限制为1000
			if (strpos($value['data'],"sql:")>-1)
			{
				$datavalue=explode(":",$value['data']);
				$sql = $datavalue[1];

				$sql = $this->parseExpression($sql);
				
				$limitcount=999;//下拉列表最多显示项目数
				$totalnumber=Tool::get1bysql("select count(*) from  (" . $sql . ") a");
				$sql = $sql." limit 0,".$limitcount;
				$Model = new Model();
			    $data = $Model->query($sql);
				$keys = array_keys($data);
			    $nn = count($keys);
                for ($g=0; $g<$nn; $g++)
				{ 
                    $x = count($data[$g]);
					$y=array_keys($data[$g]);
					if($x<2)
					{   
					    $vdata.= ",".$data[$g][$y[0]];
					}
					if($x>=2)
					{
						$vdata.= ",".$data[$g][$y[0]].":".$data[$g][$y[1]]; 
                    }	 
                }
				if ($totalnumber>$limitcount)
				{
					$vdata.= ",(数据太多，无法显示第".$limitcount."个以下的项目)"; 
				}
                $m["fields"][$i]['data']=$vdata;
                $m["fields"][$i]['data_init']=$value['data'];//前台智能选择组件需要
				$vdata="";
			}

			//下面解析defaultdata属性
			if ($value['defaultdata'])
			{
				$v=$value['defaultdata'];
				
				$v=$this->parseExpression($v);
				$m["fields"][$i]['defaultdata']=$v;
				
				if (strpos($value['defaultdata'],"sql:")>-1)
				{
					$datavalue=explode(":",$value['defaultdata']);
					$sql = $datavalue[1];

					$sql = $this->parseExpression($sql);
					$sql = $sql." limit 0,1";
					$data = Data::sql1($sql);
					
					$m["fields"][$i]['defaultdata']=$data;
				}
				
			}
		}
		return $m;
	}
	
	
	//获得模型数据给前端
    public function getFunctionObject(){
		$modelid = $_REQUEST['table'];
        if($modelid == null) {$modelid = $_REQUEST['modelid'];	}	
		$data = $this->getGoodModel($modelid);
		echo json_encode($data);
	}

	//解析搜索词汇。搜索词中的*表示and关系，空格表示or关系。例如：“山东*大学 研究院”表示某个字段同时包含“山东”和“大学”，或者包含“研究院”的记录
	public function getFilter($modelid,$word)
	{
		//判断内容里有没有中文-GBK (PHP) 
		function is_chinese($s){ 
			 return preg_match('/[\x80-\xff]./', $s); 
		}
	
		$fields=$this->getTableInfo($modelid,"fieldsArray");
		$r="";//条件
		$word=trim($word);
		$arr0=explode(" ",$word);//首先用空格分割第一层
		$hasChinese=is_chinese($word);
		foreach($arr0 as $searchWord)
		{
			$arr=explode("*",$searchWord);//然后用*分割字符串第二层
			foreach($fields as $v)
			{
				if ($v[forsearch]=="true")
				{	
					if ($hasChinese && ($v['fieldtype']=='datetime' || $v['fieldtype']=='date' || $v['type']=='date'  || $v['type']=='datetime' ))
						continue;				
					$fieldfilter='';
					for($i=0;$i<count($arr);$i++)
					{
						$oneword=trim($arr[$i]);
						
						if (($v["type"]=='radio' || $v["type"]=='dropdown' || $v["type"]=='checkboxlist') && $v["data"])
						{
							$field_data=trim($v["data"]);
							$data_arr=explode(",",$field_data);
							foreach($data_arr as $oneitem)
							{
								$oneitem_arr=explode(":",$oneitem);
														 
								if (count($oneitem_arr)==1) //下拉列表数据存储值与显示值一样的情况,data值例子：男,女
								{
									if (strpos($oneitem_arr[0],$oneword)>-1)
									{
										if ($v["type"]=='checkboxlist' || $v["type"]=='radio') //多选项
											$fieldfilter="concat($v[id],',') like '%".$oneitem_arr[0]."%'";
										else
											$fieldfilter=$v[id]." ='".$oneitem_arr[0]."'";
									}
								}
								else //下拉列表数据存储值与显示值不一样的情况,data值例子：1:男,2:女
								{
									if (strpos($oneitem_arr[1],$oneword)>-1)
									{
										if ($v["type"]=='checkboxlist')
											$$fieldfilter=" concat($v[id],',') like '%".$oneitem_arr[0]."%'";
										else
											$fieldfilter=$v[id]." ='".$oneitem_arr[0]."'";
										
									}
								}
							}
						}
						else //普通字段，字段存储值与显示值一直的情况
						{
							$fieldfilter .=$v[id]." like '%$oneword%'";	
						}
						if((count($arr)-1)!=$i){
							$fieldfilter .=" and ";
						}						
					}
					if ($fieldfilter)
					{
						$fieldfilter ='('.$fieldfilter.')';
						if ($r)
							$r .=" or ".$fieldfilter;
						else
							$r .=$fieldfilter;
						
					}					


				}
			}
		}
		
		return $r;
	}




	//getDataList是比较早的方法，已经被getData代替
	public function getDataList($table)
	{
        if($table==null) {
    		$table = $_REQUEST['table'];
        }
		$this->getData($table);
		
    }

	//MCSS模型核心方法之一。获得模型的数据记录，返回记录集合
	public function getData($modelid)
	{	
		if (!$modelid)
			$modelid = $_REQUEST['modelid'];
		//权限检查
		$err=MCSS_Access::checkAccess($modelid);
		if ($err){
			echo $err;
			exit;		
		}
		$page=$_REQUEST['page'];
		$pagerows=$_REQUEST['pagerows'];	
		if(stripos($type,"MSIE") > 0){
			$filterFromClient=($_REQUEST['filter']);//本来应该用解码函数rawurldecode 或urldecode，但用了反而不行，不知道为何
			$word=($_REQUEST['sosoword']);//简单搜索过滤词
		}else{
			$filterFromClient=($_REQUEST['filter']);//本来应该用解码函数rawurldecode 或urldecode，但用了反而不行，不知道为何
			$word=($_REQUEST['sosoword']);//简单搜索过滤词
		}
		$model=$this->getGoodModel($modelid);

		if (C("mcss_log_getdata"))	System::addlog('获取数据:'.$model['title']);
		
		if ($pagerows==""){
			$pagerows=$model['pagerows'];
		}
		if ($pagerows=='')
			$pagerows=10;
	
		if ($page==""){
			$page=1;
		}
		
		$rowfrom = ($page-1) * $pagerows;
		$rowlength = $page * $pagerows;
		$limit=strval($rowfrom).','.strval($pagerows);
		if ($pagerows=="-1")
			$limit="";
        $tablename = $model['tablename']; 
		if ($_REQUEST['sql'])
			$model_sql=$_REQUEST['sql']; 
		else
			$model_sql=$model['sql']; 
		
		$keyfield=$model['keyfield']; 
		if (($keyfield=='' || $keyfield==null) && $this->getFieldProp($model,'id','id'))
			$keyfield='id';	

		$handedorder = $_REQUEST['orderby'];//前台手工改变排序
		if ($handedorder=='undefined')
			$handedorder="";			
		if($handedorder)
		{
			$orderby=$handedorder;
		}
		else
		{
			$orderby=$model['orderby'];
			if($orderby=="" && $keyfield=="id"){
				$orderby =$keyfield." desc";
			}
		}
			
		$filter="";//最终的sql过滤条件

		$filter=$this->getSqlFilter($modelid,$model,$word,$filterFromClient);	
		if ($_REQUEST['sql'])
		{
			$sql=$_REQUEST['sql'];
			$sql=$this->getsql($modelid,$tablename,$_REQUEST['sql'],$filter,$orderby,$limit);
		}	
		else
			$sql=$this->getsql($modelid,$tablename,$model_sql,$filter,$orderby,$limit);
		System::log('getData:'.$sql);
		$t = new Model();
		$sql=$this->dealSpecialChar($sql);	
		$rows = $t->query($sql);
		$err=mysql_error();
		if ($err)
		{
			System::log($err);
			$err=array(array('err'=>"查询数据错误",'errcode'=>'sqlerror'));
			echo json_encode($err);			
		}
		else		
			echo json_encode($rows);
    }
    

	//拼凑出sql语句
	function getsql($modelid,$tablename,$sql,$filter,$orderby,$limit)
	{
		if ($sql!="")
		{ 
			if ($filter!="")
				$sql ="select * from (". $sql.") a where (".$filter.")";
			if ($orderby !='')
				$sql .=' order by '.$orderby;
			if ($limit!="")
				$sql .=' limit '.$limit;
		}
		else
		{
			$fields=$this->getVisibleFields($modelid);
			$sql="select ".$fields." from ".$tablename;
			if ($filter)
				$sql .=" where ".$filter;
			if ($orderby)
				$sql .=" order by ".$orderby;
			if ($limit)
				$sql .=" limit ".$limit;
		}
		return $sql;
	}
	
	//获得指定模型的可见物理字段列表
	function getVisibleFields($modelid)
	{
		$model=$this->getGoodModel($modelid);
		$fields="";
		foreach($model['fields'] as $field)
		{
			//非虚拟字段且可见
			if ($field['virtualfield']!="true")
			{
				if ($field['id'])
				{
					if ($fields)
						$fields.=",";
					$fields.=$field['id'];
				}
			}
		}
		return $fields;
	}
	
	//计算前端发来的请求需要的sql的where语句
	function getSqlFilter($modelid,$model,$searchWord,$filterFromClient)
	{
		$filter="";//最终的sql过滤条件
		$filter=$this->addCondition($filter,$model["filter"]);
		if($searchWord)
		{	
			if(strpos($searchWord,"<yh>")>-1) //高级搜索里用的
			{   
				$searchWord = str_replace("<yh>","'",$searchWord);
				$filter=$this->addCondition($filter,$searchWord);
			}
			else
			{
				$seachCond=$this->getFilter($modelid,$searchWord);
				System::addlog($seachCond.":".$searchWord."by juzi");
				$filter=$this->addCondition($filter,$seachCond);
			}
		}

		if (filterFromClient && $filterFromClient!='undefined')
		{
			$filter=$this->addCondition($filter,$filterFromClient);
		}	
		$filter=str_replace("%25","%",$filter);
		$filter=$this->dealSpecialChar($filter);
		return $filter;
	}
	
	//把新的sqlwhere语句加到已有的where语句后面
	function addCondition($oldCondition,$newCondition)
	{
		$r="";
		if($oldCondition && $newCondition){
			$r ="(".$oldCondition.") and (".$newCondition.")";
		}
		else
		if ($oldCondition && !$newCondition)
			$r =$oldCondition;
		else
		if (!$oldCondition && $newCondition)
			$r =$newCondition;
		
		return $r;
	}
	

	//删除多条记录
	function deleterows()
	{
		$err="";//返回结果。空表示没有错误
		$modelid = $_REQUEST['modelid'];
		$orderno = $_REQUEST['orderno'];
		$rowIds=$_REQUEST['rowIds'];
		if(isset($rowIds))
		{
			$model=$this->getGoodModel($modelid);
			$tablename=$model["tablename"];	
			// $beforedeleted = $model["beforedeleted"];
			// $beforedeleted = $this->getEvent($beforedeleted);
			// if ($beforedeleted!="")
			// {
					// $err = $beforedeleted($orderno,$tablename,$rowIds);
			// }
			//if ($err=="")
			//{
				// $afterdeleted = $model["afterdeleted"];
				// $afterdeleted = $this->getEvent($afterdeleted);
				// if ($afterdeleted!="")
				// {
					// $err = $afterdeleted($rowIds);
				// }
			
				//M($tablename)->delete($rowIds);

				$keyfield=$model["keyfield"];
				if (!$keyfield)
					$keyfield="id";
				$sql="delete from $tablename where $keyfield in ($rowIds)";
				System::log($sql);
				$n=Tool::executesql($sql);//这里假设id是数字类型，如果是字符就会错误
				if ($n>0){
					$sql_after_deleted=$model["sql_after_deleted"];
					if ($sql_after_deleted){ 
						$arr=explode(",",$rowIds);
						foreach($arr as $item)
						{
							$sql_after_deleted=Expression::parseExpression($sql_after_deleted);
							//$sql_after_deleted支持多个sql语句,用分号分开--by 刘兆菊
							if(strpos($sql_after_deleted,";")>-1)
							{
								$sqlarr=explode(";",$sql_after_deleted);
								for($k=0;$k<count($sqlarr);$k++)
								{
									$sql=str_replace("recordid()",$item,$sqlarr[$k]);
									System::log($sql);
									Tool::executesql($sql);
								}
							}
							else
							{
								$sql=str_replace("recordid()",$item,$sql_after_deleted);
								System::log($sql);
								Tool::executesql($sql);
							}
							
						}
					}
				}	 
			
			//}
		}
		if ($err=="")
			$err="ok";
		echo $err;
	}

  //获取事件名称。现在用不上了
  function getEvent($event)
  {
  	$r="";
		if(function_exists($event))
		{
			$r=$event;
		}
		else
		{
			$arr=explode("->",$event);
			if (count($arr)==2)
			{
				include_once $arr[0];//引入包含函数的php文件
				$r =$arr[1];
			}			 	
  	}
  	return $r;
  }
  
  
	
	//更新修改过的记录。表格编辑器MCSSEditor和grideditor需要，但这两个文件现在还没用上
    public function save_edited_data()
    {
    
    
        $modelid = $_REQUEST['modelid'];
        $tablename = $_REQUEST['tablename'];
       	$eidtedata = $_REQUEST['eidtedata'];

        //下面把传来的修改过的数据组成的数组更新到数据库，每一条更新过的记录格式是：
        // 这是前台收集数据的代码：
        //var eidtedata={"mcss_recordtype":"UPDATE","mcss_rowindex":1,"code":"123","recordid":"17888","name":"456"}~|~{"mcss_recordtype":"NEW","mcss_rowindex":11,"code":"aaa","name":"bbb"}~|~
        
        $r="";
        $repl = array("<yh>"=>"'");
        $eidtedata=strtr($eidtedata,$repl);
        $record_array=explode("~|~",stripslashes($eidtedata));
		$afterupdated = $this->getTableInfo($modelid,"afterupdated");
		$afterupdated = $this->getEvent($afterupdated);
		$keyfield=$this->getTableInfo($modelid,"keyfield");
		$model=$this->getGoodModel($modelid);
		if ($keyfield=='' || $keyfield==null)
			$keyfield='id';
		$errinfo="";//失败信息
		$recordUpdated=0;//收影响的记录数
        foreach($record_array as $record)
        {
            if($record!="")
			{
                $rec=json_decode($record,true);
                
                if ($rec["mcss_recordtype"]=="UPDATE")//更新记录
                {
	                $rec=$this->addSystemFields($model,$rec,"SYS_EDITUSER,SYS_EDITTIME");
                	$temp="";
                	foreach($rec as $key=>$value)
                	{                	                		
                		$fieldtype=$this->getFieldProp($model,$key,'type');
						$value=$this->getSystemDefaultValue($fieldtype,$value);
                		if ($key!="mcss_recordtype" && $key!="recordid" && $key!="mcss_rowindex")
                		{
                			if ($temp)
                				$temp.=",";
                			$temp.=$key."='$value'";
                		}
                	}
					$sql="update ".$tablename." set $temp where $keyfield='".$rec["recordid"]."'";
					
           		}
           		else
                if ($rec["mcss_recordtype"]=="NEW")//添加记录
           		{
	                $rec=$this->addSystemFields($model,$rec,"SYS_ADDUSER,SYS_ADDTIME,SYS_EDITUSER,SYS_EDITTIME,SYS_ORGID");
           		
                	$fields="";
                	$values="";
                	foreach($rec as $key=>$value)
                	{
                		$fieldtype=$this->getFieldProp($model,$key,'type');
						$value=$this->getSystemDefaultValue($fieldtype,$value);
                	
                		if ($key!="mcss_recordtype" && $key!="recordid" && $key!="mcss_rowindex")
                		{
                			if ($fields)
                				$fields.=",";
                			$fields.=$key;
                			if ($values)
                				$values.=",";
                			$values.="'".$value."'";
                		}
                	}
					$sql="insert into ".$tablename."($fields) values ($values)";
           		
           		}
           		else
                if ($rec["mcss_recordtype"]=="DELETE")//添加记录
           		{
                	$ids="";
                	foreach($rec as $key=>$value)
                	{
                		if ($key=="recordid")
                		{
                			if ($ids)
                				$ids.=",";
                			$ids.="'".$value."'";
                			
                		}
                	}
					$sql="delete from ".$tablename." where $keyfield in ($ids)";

           		}
            
				//echo $sql;exit;
				$Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
				$n = Data::sql($sql);
				//return;
				if ($n>0) 
					$recordUpdated+=$n;
				else
					if ($n)
					$errinfo.="  ".$n;
				
				System::log($sql.$n);
				
				$newid=mysql_insert_id();				
				//解析执行记录新增后要额外执行的sql语句。sql在模型中定义
				$sql_after_inserted=$model["sql_after_inserted"];
				if ($sql_after_inserted)
				{ 
					$this->sql_after_inserted($sql_after_inserted,$tablename,$keyfield,$newid);
				}
				
				if ($afterupdated!="")
				{
					$err = $afterupdated($tablename,$rec["id"]);
					if ($err!="")
					{
						$r = $err;
					}
				}
			}
            
        }
       if ($recordUpdated==0 && $errinfo=='')
        	$r="0";
       else
    	if ($errinfo)
        	$r="保存失败：".$errinfo;

		if ($r=="")
			$r="ok";
        echo $r;//返回ok空表示成功更新，否则表示错误信息
    }
	
	//如果模型包含了系统字段，则自动给这些字段变成空，供后面赋予特定值
	function addSystemFields($model,$record,$fields)
	{
		$fields=explode(",",$fields);
		foreach($fields as $field)
		{
	         if ($this->getFieldProp($model,$field,"type")==$field)
		         $record[$field]="";
		}
	    return $record;
	}
	
	public function saveFormData()
	{
		//根据模型ID获得表单各字段值
		$modelid=$_REQUEST["modelid"];
		$fields = $this->getTableInfo($modelid,"fieldsArray");
        foreach($fields as $v)
        {
			echo $v[id].':'.$_POST[$v[id]].'<br />';
        }	
	}
	
	//打开标准MCSSTable列表.今后应该用System::openModel代替这个方法
	public function openModel($modelid){
		
		if (!isset($modelid))
			$modelid=$_REQUEST["model"];
		$url= "http://".$_SERVER['HTTP_HOST'].__APP__.'/List/List/list2?param:modelid='.$modelid;
		header('Location:'.$url);
	}
	//把指定的网页导入到word文档
	public function exporttoword()
	{
		Vendor("world.exptoworld");//导入MhtFileMaker
		$content = $_REQUEST['content'];//获取导入world中的内容
		$content = iconv("utf-8","gb2312",$content); 
		$path=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/temp/';//导出world的路径
		$fileContent = getWordDocument($content,$_SERVER['DOCUMENT_ROOT'],false);
		$fp = fopen($path."exp.doc",'w');
		fwrite($fp,$fileContent);
		fclose($fp);
		echo trim('exp.doc');
	}
	
	//把指定的模型的数据导出到xls文件	
	public function exporttoxls($modelid){
		if (!$modelid)
			$modelid = $_REQUEST['modelid'];
		//权限检查
		$err=MCSS_Access::checkAccess($modelid);
		if ($err)
		{
			echo $err;
			exit;		
		}	
		$model=$this->getGoodModel($modelid);
		$fields=$model['fields'];
		foreach($fields as $field)
		{
			if(!$field['isvisible'] || $field['isvisible']=='true')
			{
				$array[$field['id']]=$field['name'];
			}
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

			$filter=$this->getSqlFilter($modelid,$model,$word,$filterFromClient);	
			$sql=$this->getsql($modelid,$tablename,$model_sql,$filter,$orderby,"0,5000");
			$m=new Model();
			$data=$m->query($sql);
		}else{
			$data=Data::mcssStrToArray($data);
		}
		Vendor("uni_exportExcel");//导入phpexcel
		$path=$_SERVER['DOCUMENT_ROOT'].__ROOT__;
		$filetype = $_REQUEST["filetype"];//获取导出文件的类型
		echo '导出数据成功!;'.exportExcel($data,$array,$path,$filetype);
	}
 	
	//把指定的模型的数据导出到csv格式文件
	public function exporttocsv()
	{
   		$modelid = $_REQUEST['modelid'];
		$model=$this->getGoodModel($modelid);
		$sql=$model["sql"];
		if ($sql=="")
		{
			$tablename = $model["tablename"];
			
			$sql="select * from ".$tablename;
			$filter = $model["filter"];
			if ($filter !='') 
				$sql .=" where (".$filter.")";

			$orderby=$model["orderby"];
			if ($orderby !='')
				$sql .=' order by '.$orderby;		
        }
		$fields='';
		$fieldnames='';
		$fieldArr=$model["fields"];
		foreach($fieldArr as $field){
			if ($fields!='')
				$fields.=',';
			if ($fieldnames!='')
				$fieldnames.=',';
			$fields.=$field["id"];	
			$fieldnames.=$field["name"];	
		}
		$params['sql']=$sql;
		$params['fields']=$fields;
		$params['fieldnames']=$fieldnames;
		$params['filename']=$modelid;
		import("@.ORG.Export");
		$filename=Export::exportToCSV($params);
	}
	
	//打开自动表单的新增记录页面
	public function addRecord()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));	
		$this->display();
	}
	
	//打开自动表单的查看记录页面
	public function viewRecord()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));	
		$this->display();
	}	
	
	//显示所有模型。用于测试是否所有模型有语法错误
	public function showModels()
	{
		include_once("LoadAllModels.php");	
		$modellist=MCSSModel::getAll();
		print_r($modellist);
	}
	
	//把所有模型一个个存到文件中。用于测试
	public function saveModelsToFiles()
	{
		//临时方法，以后可以删除
		include_once("LoadAllModels.php");	
		$modellist=MCSSModel::getAll();
 
		foreach($modellist as $k=>$v)
		{ 
			echo '<br />';
			//$root="D:\wamp\www\jusaas\crm\Lib\Action\MCSSEngine\Models\\";
			$root=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME."/Lib/Model/models/";

			$filename=$root.$k.".php";
 			$str=var_export ($v,true);
			$str="<?php return ".$str." ?>";
 
			file_put_contents($filename,$str);
			if (file_exists($filename))
				echo $filename;
			
		}
		echo 'done!';

	}
	
 	
	//字段唯一值校验
	public function checkUnique()
	{
		$modelid=$_REQUEST['modelid'];
		$field=$_REQUEST['field'];
		$value=trim($_REQUEST['value']);
		$recordid=$_REQUEST['recordid'];
		echo $this->returnFieldUniqueCheck($modelid,$field,$value,$recordid);
	}
	
	
	//字段唯一值校验
	public function returnFieldUniqueCheck($modelid,$field,$value,$recordid)
	{
		$model=$this->getGoodModel($modelid);
		$table=$model['tablename'];
		$m=new Model($sql);
		
		$oldvalue=null;
		if ($recordid)
		{
			$keyfield='id';
			if ($model['keyfield'])
				$keyfield=$model['keyfield'];
			$sql="select ".$field." from ".$table." where ".$keyfield."='".$recordid."'";
			$rows=$m->query($sql);
			$oldvalue=$rows[0][$field];
		}

		$sql="select ".$field." from ".$table." where ".$field."='".$value."'";
		$rows=$m->query($sql);
		$n=count($rows);

		if (!$recordid && $n >0 || $recordid && $n >0 && $oldvalue!=$value)
		{
			return 0; //表示未通过校验
		}
		else	
			return 1;//表示通过校验
	}
	
	//打开标准的mctable列表
	function  list2()
	{	
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}
	function  printList()
	{	
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}

	//打开标准的mctable列表
	function list1()
	{	
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}

	//显示功能菜单中type为html的内容
	function  showhtml()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}
	
	//删除一条记录
	function deleteRecord()
	{
		$modelid=$_REQUEST["modelid"];
		$recordid=$_REQUEST["recordid"];
		$model=$this->getGoodModel($modelid);
		$tablename=$model["tablename"];
		if ($tablename){
			$keyfield=$model["keyfield"];
			if (!$keyfield)
				$keyfield="id";
			$n=Tool::executesql("delete from $tablename where $keyfield='$recordid'");
			if ($n>0){
				$sql=$model["sql_after_deleted"];
				if ($sql){ 
					$sql=Expression::parseExpression($sql_after_deleted);
					$sql=str_replace("recordid()",$recordid,$sql);
					Tool::executesql($sql);
				}
			} 
			echo $n;
		}
	}
	
	//根据查询模型的ID列表获得这些模型的过滤选项记录并返回给前端。陈坤极
	function getFilterData()
	{
		$filterModels=$_REQUEST["filterModels"];
		if (!isset($filterModels))
			$filterModels="";
		$filterModels=Expression::strToArrStr($filterModels);
		$sql="select a.*,b.code as modelcode,b.datatype,b.unit from sys_querymodeldetail a left join sys_querymodel b on b.id=a.parentid where a.parentid in  (select id from sys_querymodel where code in (".$filterModels.")) order by a.sort";
		//echo $sql;exit;
		echo Tool::getDataJSON($sql);
		
	}
	
	//autoform表单用到的上传文件
	function saveUploadedFile()
	{
		$modelid=$_REQUEST["modelid"];
		$fieldid=$_REQUEST["fieldid"];
		$recordid=$_REQUEST["recordid"];
		$files=$this->uploadFile($_FILES,$modelid,$fieldid,$recordid);
		$result=$files;
		$arr=explode(":",$files);
		if (count($arr)>1)
		{
			if ($arr[0]=="ok")
			 	$result="<span style='color:green;font-size:12px'>上传成功</span>";
			else
			if ($arr[0]=="err")
			 	$result="<span style='color:red;font-size:12px'>".$arr[1]."</span>";
			 
		}
		echo "<input type='hidden' id='uploadresult' value='$files' /><a id='sccg' class='sccg'>$result</a>";
	}
	
	//获取表格的所有工作表名称
	function getSheetNames(){
		Vendor("imp");//引用imp文件
		$filename = $_REQUEST['file'];//获得上传文件的名称
		$data = getExcelSheetNames($filename);
		echo json_encode($data);
	}
	//获取相关模型的所有字段名称
	function getModelNames(){
		$modelid = $_REQUEST['modelid'];
		$model=$this->getGoodModel($modelid);//根据模ID型获取模型
		$fields=$model['fields'];
		echo json_encode($fields);
	}
	//获取表格的预览数据
	function getViewData(){
		Vendor("imp");//引用imp文件
		$filename = $_REQUEST['file'];//获得上传文件的名称
		$sheetname = $_REQUEST['sheetname'];//获得上传文件选中工作表的表名
		$data = getsixdata($filename,$sheetname);//获得读取数据表得到的数组
		$i=0;
		$last = count($data);
		foreach($data[0] as $key=>$value){//获取表头列
			$data[$last][$i]=$key;
			$i++;
		}
		echo json_encode($data);
	}
	 //读取xls导入数据库方法
	function xlsTosql()
	{
		Vendor("imp");//引用imp文件
		$filename = $_REQUEST['file'];//获得上传文件的名称
		$sheetname = $_REQUEST['sheetname'];//获得上传文件选中工作表的表名
		$data = impExcel($filename,$sheetname);//获得读取数据表得到的数组
		$modelid=$_REQUEST['modelid'];//获取前台传来的短信模型ID
		$field=$_REQUEST['field'];//获取前台传来的要导入的字段
		$fieldvalue=$_REQUEST['fieldvalue'];//获取前台传来的字段的真正的值的字段的名称
		$repeatfield=$_REQUEST['repeatfield'];//获取前台传来的重复字段
		$repeatstyle=$_REQUEST['repeatstyle'];//获取前台传来的重复数据处理方式
		$model=$this->getGoodModel($modelid);//根据模ID型获取模型
		$tablename=$model['tablename'];//根据模型获取表名称
		$fields=$model['fields'];//获取到表名称的字段列表
		$m=new Model();
		
		$field = explode(',',$field);
		$fieldvalue = explode(',',$fieldvalue);
		//获取要导入的字段
		for($i=0;$i<count($field);$i++){
			foreach($fields as $modelfield)
			{
				if($modelfield['name']==$field[$i]){
					if($fieldsql)//拼接插入数据库的sql前半段语句
						$fieldsql.=',';
					$fieldsql.=$modelfield['id'];
					
					if($fieldtype)//拼接各字段的类型
						$fieldtype.=',';
					$fieldtype.=$modelfield['type'];
					break;
				}
			}
		}
		//判断如果sql语句里有系统字段则不加入，没有就追加
		$fieldtypeArr = explode(',',$fieldtype);
		$fieldsqlArr = explode(',',$fieldsql);
		for($i=0;$i<count($fieldtypeArr);$i++){
			if($fieldtypeArr[$i]=='SYS_ADDUSER' || $fieldtypeArr[$i]=='SYS_EDITUSER' || $fieldtypeArr[$i]=='SYS_ADDTIME' ||
			$fieldtypeArr[$i]=='SYS_EDITTIME' || $fieldtypeArr[$i]=='SYS_ORGID'){
				$fieldsyshas[$fieldtypeArr[$i]]=$fieldsqlArr[$i];
			}
		}
		foreach($fields as $modelfield){
			if($modelfield['type']=='SYS_ADDUSER' || $modelfield['type']=='SYS_EDITUSER' || $modelfield['type']=='SYS_ADDTIME' || $modelfield['type']=='SYS_EDITTIME' || $modelfield['type']=='SYS_ORGID'){
				$fieldsysall[$modelfield['type']]=$modelfield['id'];
			}
		}
		//获取重复字段在数据库中的字段名称
		if($repeatfield){
			foreach($fields as $modelfield)
			{
				if($modelfield['name']==$repeatfield){
					$repeatfielddata = $modelfield['id'];
					break;
				}
			}
		}
		$fieldsqlarr=explode(',',$fieldsql);//获取插入数据库的字段数组
		$inserterrors=0;//导入数据库错误的数量
		$inserttrueth=0;//导入数据库正确的数量
		$ingorenum=0;//忽略重复数据的数量
		$putnum=0;//覆盖重复数据的数量
		//导入数据库
		for($i=0;$i<count($data);$i++){
			if(!$repeatfield || $repeatstyle=='add'){//当重复字段为空或者处理方式为新增时直接插入
				$result = $this->addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall);//调用新增数据的方法
				if($result==0)
					$inserterrors++;
				else
					$inserttrueth++;
			}else{//当重复字段有值时进行两种方式的处理：忽略或者覆盖
				$sql="select count(*) from $tablename where $repeatfielddata = '".$data[$i][$repeatfield]."'";//根据选中字段的值来获得到是否有相同的值
				$result = Tool::get1bysql($sql);
				if($result > 0){//当已存在重复值时
					if($repeatstyle=='put'){
						$updatefield = '';
						for($k=0;$k<count($fieldsqlarr);$k++){//获取更新语句的前段语句
							if($updatefield)
								$updatefield.=',';
							$updatefield.=$fieldsqlarr[$k]."="."\"".$data[$i][$fieldvalue[$k]]."\"";
						}
						$sql = "update $tablename set $updatefield where $repeatfielddata = '".$data[$i][$repeatfield]."'";//更新重复字段的每一个值
						$result = $m->execute($sql);
						if($result > 0)
							$putnum+=$result;
					}else{
						$ingorenum++;
						continue;
					}
				}else{
					$result = $this->addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall);//调用新增数据的方法
					if($result==0)
						$inserterrors++;
					else
						$inserttrueth++;
				}
			}
		}
		$this->errorslist[count($this->errorslist)] = $sheetname.'|'.count($data).'|'.$inserttrueth.'|'.$inserterrors.'|'.$ingorenum.'|'.$putnum;
		echo json_encode($this->errorslist);
	}
	//新增数据
	function addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall){
		$m = new Model();
		for($k=0;$k<count($fieldvalue);$k++){
			if($fieldsqlvalue)
				$fieldsqlvalue.=',';
			$fieldsqlvalue.="\"".$data[$i][$fieldvalue[$k]]."\"";
		}
		$diffArr = array_diff($fieldsysall,$fieldsyshas);
		if(count($fieldsyshas)==0)
			$diffArr = $fieldsysall;
		foreach($diffArr as $key=>$value){
			if($key=='SYS_ADDUSER')
				$fieldsysvalue = System::loginuser();
			else if($key=='SYS_EDITUSER')
				$fieldsysvalue = System::loginuser();
			else if($key=='SYS_ADDTIME')
				$fieldsysvalue = Expression::now();
			else if($key=='SYS_EDITTIME')
				$fieldsysvalue = Expression::now();
			else if($key=='SYS_ORGID')
				$fieldsysvalue = System::orgid();
			$fieldsql.=",$value";
			$fieldsqlvalue.=",'$fieldsysvalue'";
		}
		$sql="insert into $tablename($fieldsql)values($fieldsqlvalue)";
		System::log($sql);
		$result = $m->execute($sql);
		$this->errorslist[$i]['row']=$i+2;
		$this->errorslist[$i]['errorinfo']=$m->getDbError();
		$this->errorslist[$i]['datainfo']=$data[$i];
		return $result;
	}
	
	//导入数据是否覆盖(excel中列的名称必须和模型中的name字段列名相同)
	function repeatxlsTosql()
	{
		Vendor("imp");//引用imp文件
		//$filename = $_REQUEST['file'];//获得上传文件的名称
		$filename = '2012_11_27_54956055.xls';
		$data = impExcel($filename);//获得读取数据表得到的数组
		echo $data;exit;
		//$modelid=$_REQUEST['modelid'];//获取前台传来的短信模型ID
		$modelid = 'mj_reporter';
		$model=$this->getGoodModel($modelid);//根据模ID型获取模型
		$tablename=$model['tablename'];//根据模型获取表名称
		$fields=$model['fields'];//获取到表名称的字段列表
		$status = '否';
		//$status = $_REQUEST['status']; //获取是否覆盖状态
		$keyfield = '邮箱';
		//$keyfield = $_REQUEST['keyfield']; //获取覆盖依据的唯一字段
		$fields_sql;//要插入数据库表的列名称
		$filter; //查询数据库数据的过滤条件
		$fieldsValues;
		$m=new Model();
		$num = count($data);
		
		//获取要导入的数据列名称
		foreach($fields as $field)
		{
			if($field['name'] == $keyfield)
			{
				$filter = $field['id'];
			}
			foreach($data[0] as $key=>$value){
				if($field['name']==$key){
					if($fields_sql)
						$fields_sql.=',';
					$fields_sql.=$field['id'];
				}
			}
		}
		//导入数据
		$j;
		for($i=0;$i<count($data);$i++)
		{	
			$k = 0;
			$values_sql='';
			$fieldsValues ='';
			foreach($data[$i] as $value)
			{
				if($values_sql)
						$values_sql.=",";
				$values_sql.="'".$value."'";
			}
			//拼接update设置的值 例如：(name='名称',age='12')
			foreach($fields as $field)
			{
				if($fieldsValues)
					$fieldsValues.=",";
				if($k == 0)
					$fieldsValues ='';
				else
					$fieldsValues .=$field['id']."='".$data[$i][$field['name']]."'";
				$k++;
			}
			$values_sql=str_replace('/','',$values_sql);
			if($status == '是')
			{
				$sql = "select * from $tablename where $filter = '".$data[$i][$keyfield]."'";
				$res = $m->query($sql);
				if($res)
				{
					$sql = "update $tablename set $fieldsValues where $filter = '".$data[$i][$keyfield]."'";
					$result = $m->execute($sql);
					if($result==0)
						System::log($sql);
					else
					{						
						$j++;
						System::log($sql);
					}
				}else{
				
					$sql="insert into $tablename ($fields_sql)values($values_sql)";
					$result = $m->execute($sql);
					if($result==0)
						System::log($sql);
					else
					{
						$j++;
						System::log($sql);
					}
				}
			}else
			{
				$sql="insert into $tablename ($fields_sql)values($values_sql)";
				$result = $m->execute($sql);
				if($result == 0)
					System::log($sql);
				else{
					$j++;
					System::log($sql);
				}
			}
		}
		if($num == $j)
		{
			echo 0;
		}
	
	}
	
	//处理引号
	function dealSpecialChar($str)
	{
	    $repl = array("<yh>"=>"'",'\"'=>"'","\'"=>"'");
        return $str=strtr($str,$repl);
	}
	//得到sql语句的执行结果(插入，删除)
	function excuteSql()
	{
		$sql = $_REQUEST['sql'];
		System::log('得到sql语句的执行结果'.$sql);
		$m = new Model();
		$result = $m->execute($sql);		
		echo $result;
	}
	//得到cookie存入数据库的值
	function getValueByKey(){
		$cookiekey = $_REQUEST["cookiekey"];
		$sql = "select cookievalue from sys_recent where cookiekey = '$cookiekey'";
		echo Tool::get1bysql($sql);
	}
}

?>