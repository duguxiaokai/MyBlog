<?php 
class ModelMakerAction extends Action{
	//暂时不用
    public function _initialize123(){
        // header("Content-Type:text/html; charset=utf-8");
		// if ($_SESSION['loginuserrole']!="admin" && $_SESSION['loginuserrole']!="chengxuyuan")
		// {	
			// echo "只有角色为admin或者研发人员的用户才能使用该功能。请<a href='".__APP__."/Home/Index/login/fromurl/localhost.xigan.jusaas.xigan.index.php.xigan.mcss.xigan.model.xigan.menutree'>登录</a>";
			// exit;
		// }
		 
    }	
	function index()
	{
		$this->redirect("/Model/modelmanager");
	}
	function modelpath()
	{
		$root=__ROOT__;
		$root=substr($root,1,strlen($root));
		$doc_root=$_SERVER['DOCUMENT_ROOT'];
		if (substr($doc_root,strlen($doc_root)-1)=="/")
			$doc_root=substr($doc_root,0,strlen($doc_root)-1);
		return $root=$doc_root.'/'.$root.'/'.APP_NAME."/Lib/Model/Models/";
	}

	function modelpath1()
	{
		$root=__ROOT__;
		$root=substr($root,1,strlen($root));
		$doc_root=$_SERVER['DOCUMENT_ROOT'];
		if (substr($doc_root,strlen($doc_root)-1)=="/")
			$doc_root=substr($doc_root,0,strlen($doc_root)-1);
		return $root=$doc_root.'/'.$root.'/'.APP_NAME."/Lib/Model/";
	}
	
	public function get_subdir()
	{	
		$root=$this->modelpath();
		$files = scandir($root . $_POST['dir']);
		sort($files);
		$data=array();
		$sort=0;
		foreach($files as $file ) {
			if(file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_file($root . $_POST['dir'] . $file)) {
				$ext = preg_replace('/^.*\./', '', $file);
				//$FileID=date("Ymd-His") . '-' . rand(1,9999);
				$FileID=$sort;
				$sort++;
				//$data[$FileID]=iconv("GBK", 'UTF-8', $file);
				$data[$FileID]=$file;
			}
		}
		echo json_encode($data);exit;		
	}
	
	
	public function get_treedir()
	{
		$filter = $_REQUEST['filter'];
		System::log($filter);
		$root=$this->modelpath1();
		$data=$this->get_dir_children($root,0,$filter);
		echo json_encode($data);	
	}
	
	//判断指定模型id是否已存在
	public function modelid_exists()
	{
		$modelid = $_REQUEST['modelid'];
		$root=$this->modelpath1();
		$data=$this->get_dir_children($root,0,$filter);
		foreach($data as $model)
		{
			if ($model['name']==$modelid.'.php')
			{
				echo true;
				return;
			}
		}
		echo false;	
	}
	
	public function get_dir_children($dirpath,$id,$filter)
	{	
		$files = scandir($dirpath);
		$data=array();
		$fileindex=0;
		//$sort=$lastid+1;
		foreach($files as $file )
		{
			if (strpos($file,".svn") >-1)
				continue;
				
			$FileID=$id."-".$fileindex;
			$fileindex++;
			if ( $file != '.' && $file != '..'  && is_dir($dirpath.$file))
			{
				$data[]=array("id"=>$FileID,"name"=>$file,"parentid"=>$id);
				$sub_data=$this->get_dir_children($dirpath.$file."/",$FileID,$filter);
				$data=array_merge($data,$sub_data);
			}
			else
			if(is_file($dirpath.$file))
			{
				if (!$filter || strpos($file,$filter) >-1)
				{
					$ext = preg_replace('/^.*\./', '', $file);
					$data[]=array("id"=>$FileID,"name"=>$file,"parentid"=>$id);
				}
			}
		}
		return $data;		
	}
	//根据ModelID获得文件内容给Web前端
	public function getModel()
	{
		$root=$this->modelpath();
		$modelid=$_REQUEST['modelid'];
		$filename=$this->getModelPathByModelid($modelid);
		if (file_exists($filename))
		{
			$m=include($filename);
			echo json_encode($m);
		}else
			echo json_encode(null);
	}
	//获取指定目录下的Model,现在获得树形model的方法
	public function getModelByPath()
	{
		$path = $_REQUEST['path'];
		$sep = $_REQUEST['separator'];//分隔符
		$path =str_replace($sep,"/",$path);
		$path = $this->modelpath1().$path;
		if (file_exists($path))
		{
			$m=include($path);
			echo json_encode($m);
		}else{
			echo json_encode(null);
		}
	}
	//早先保存模型的方法,现在使用新的方法
	public function saveModel()
	{
		$str="<?php return ".$_POST['modelstr']." ?>";
		

		$modelid=$_POST['modelid'];
		$root=$this->modelpath();

		$filename=$root.$modelid.".php";

		file_put_contents($filename,$str);
		if (file_exists($filename))
			echo 'ok!';
		echo $str;

	}
	//现在保存模型的方法
	public function saveModelByPath()
	{
		$modelid = $_REQUEST['modelid'];
		$str="<?php return ".$_POST['modelstr']." ?>";
        $repl = array("\'"=>"'",'\"'=>"\"");
        $str=strtr($str,$repl);

		$path = $_REQUEST['path'];
		$sep = $_REQUEST['separator'];//分隔符
		$path =str_replace($sep,"/",$path);
		if (!$path) //新建文件的情况
		{
			$filename=$this->getModelPathByModelid($modelid);
		}
		else
			$filename = $this->modelpath1().$path;
		//System::log($filename);
		file_put_contents($filename,$str);
		$err="";
		if (file_exists($filename))
		{
			if ($_REQUEST['createTable']=="true")
			{
				$err1=$this->createTableByModel($modelid,$_REQUEST['delete_before_create']);
				if ($err1)
					$err.=$err1;
			}

		}
		else
			$err="创建文件失败";
		echo $err;
	}
	
	function getModelPathByModelid($modelid)
	{
		$arr=explode("_",$modelid);
		if (count($arr)>1)
		{
			$app=$arr[0];//模块前缀
		}
		else
			$app = "default";
		$path = $app."/Models/".$modelid.".php";
		return $filename = $this->modelpath1().$path;
	}

	public function deleteModel()
	{
 		$modelid=$_POST['modelid'];
		$filename=$this->getModelPathByModelid($modelid);
	
		$result = unlink ($filename);
		if ($result == false) {
				 echo '删除失败';
		} else {
				 echo '';
		}


	}
	function modelmanager()
	{
		if (C("mcss_everyone_canuse_modelmanager")!=true && System::loginuserrole()!='admin' && System::loginuserrole()!='dev')
		{
			echo "只有角色为admin和dev的用户才能执行此操作";
			exit;
		}
	
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));	
		$this->display();
	}
	public function getmd5string()
	{
		$p=trim($_REQUEST["password"]);
		echo md5($p);
	}
	
	//获得指定物理表的字段列表信息
	function getTableFields()
	{
		$table=$_REQUEST["table"];
		$link   =   mysql_connect( C("DB_HOST"),    C("DB_USER"),    C("DB_PWD")); 
 		$fields   =   mysql_list_fields( C("DB_NAME"),  $table,   $link); 
 
		$columns   =   mysql_num_fields($fields); 
		$sql="SELECT column_name, column_comment FROM information_schema.columns WHERE table_name = '$table'";
		$m=new Model();
		$notes=$m->query($sql);
		$fieldlist;
		for($i=0;$i<$columns;$i++)   
		{ 
			$field = mysql_fetch_field($fields,$i);
			$fieldText=$field->name;
			foreach($notes as $note){
				if ($note["column_name"]==$field->name)
				{
					$fieldText=$note["column_comment"];
					break;
				}
			}
			
			$editType="";
			$readonly="";
			if ($field->name=="SYS_ADDTIME"){
				$fieldText="创建时间";
				$editType=$field->name;
				$readonly="true";
			} else 
			if ($field->name=="SYS_ADDUSER"){		
				$fieldText="创建者";
				$editType=$field->name;
				$readonly="true";
			} else 		
			if ($field->name=="SYS_EDITTIME"){
				$fieldText="更新时间";
				$editType=$field->name;
				$readonly="true";
			} else 
			if ($field->name=="SYS_EDITUSER"){
				$fieldText="更新者";
				$editType=$field->name;
				$readonly="true";
			} else
			if ($field->name=="SYS_ORGID"){
				$fieldText="组织ID";
				$editType=$field->name;
				$readonly="true";
			} else			
			if ($field->type=="date"){
				$editType="date";
			} else			 
			if ($field->type=="datetime"){
				$editType="datetime";
			} 		 
			
			$fieldlist[$field->name]=array('id'=>$field->name,'name'=>$fieldText,'fieldtype'=>$field->type,'length'=>$field->max_length,'type'=>$editType,'nullable'=>$field->not_null,'unique'=>$field->unique_key,'readonly'=>$readonly);
		}  
 		echo json_encode($fieldlist);		
	}
	//获得指所有表的名称
	function getTableNames()
	{
		$link   =   mysql_connect( C("DB_HOST"),    C("DB_USER"),    C("DB_PWD")); 
 		$result = mysql_list_tables( C("DB_NAME"));
		$tablenames="";
		for ($i = 0; $i < mysql_num_rows($result); $i++)
			$tablenames.=mysql_tablename($result, $i).",";	
 		echo $tablenames;		
	}
	function getTree()
	{
		$table=$_REQUEST["table"];
		//echo $table;
		//exit;
		$id=$_REQUEST["id"];
		$pid=$_REQUEST["pid"];
		$name=$_REQUEST["name"];
		$id=$_REQUEST["id"];
		$type=$_REQUEST["type"];
		$filter=Expression::parseExpression($_REQUEST["filter"]);
		$thisorg=$_REQUEST["thisorg"];
		if ($thisorg)
		{
			if ($filter)
				$filter.="(".$filter.") and SYS_ORGID=orgid()";
			else
				$filter=" SYS_ORGID=orgid()";
		}
		$data=null;
		$sql="select $name,$id,$pid";
		$otherfield=$_REQUEST["otherfield"];
		if ($otherfield)
			$sql.=",".$otherfield;
		if ($type)
			$sql.=",$type ";
		$sql.=" from $table ";
		if ($filter)
			$sql.=" where (".$filter.")";
			
	
		if (isset($_REQUEST["orderby"]))
			$sql.=" order by ".$_REQUEST["orderby"];
		$sql=Expression::parseExpression($sql);

	    $repl = array("<yh>"=>"'",'\"'=>"'","\'"=>"'");
        $sql=strtr($sql,$repl);
		System::log($sql);
		$m=new Model();
		$data=$m->query($sql);
		echo json_encode($data);
	}

	function getFuncIdByCode()
	{
		$code=$_REQUEST['code'];
		echo  Tool::get1bysql("select id from sys_function where no='$code'");
	}
	function getmenuIdByCode()
	{
		$code=$_REQUEST['code'];
		echo  Tool::get1bysql("select id from ems_menu where no='$code'");
	}	
	
	public function openModel($modelid){
		
		if (!isset($modelid))
		$modelid=$_REQUEST["model"];
		$url= "http://".$_SERVER['HTTP_HOST'].__APP__.'/System/Model/list2?param:table='.$modelid;
		header('Location:'.$url);
	}
	function  select()
	{
	
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}
	
	//更新节点的排序,刘凯写的，不用了，可以删除
	// function updateSort()
	// {
		// $id = $_REQUEST['id'];
		// $sort = $_REQUEST['sort'];
		
		// $m = new Model();
		// $sql = 'update sys_function set sort='.$sort.' where no="'.$id.'"';
		// $m->execute($sql);
		// System::log($sql);
		// echo $id;
	// }
	
	//交换菜单顺序
	function switch_menu_sort()
	{
		$func_code1=$_REQUEST["func_code1"];
		$func_code2=$_REQUEST["func_code2"];
		$sort1=Tool::get1bysql("select sort from sys_function where no='$func_code1' ");
		$sort2=Tool::get1bysql("select sort from sys_function where no='$func_code2' ");
		if ($sort1==$sort2)
		{
			$func_code=Tool::get1bysql("select groupno from sys_function where no='$func_code1'");
			System::log($func_code);
			$this-> update_menu_sort($func_code);
			$sort1=Tool::get1bysql("select sort from sys_function where no='$func_code1' ");
			$sort2=Tool::get1bysql("select sort from sys_function where no='$func_code2' ");
		}
		Tool::sql("update sys_function set sort=$sort2 where no='$func_code1'");		
		Tool::sql("update sys_function set sort=$sort1 where no='$func_code2'");	
		echo true;
	}

	//重新编排菜单顺序
	function update_menu_sort($func_code)
	{
		$sql="select id,sort from sys_function where groupno='$func_code' order by sort";
		System::log($sql);
		$m=new Model();
		$rows=$m->query($sql);
		for($i=0;$i<count($rows);$i++)
		{
			Tool::sql("update sys_function set sort=$i where id=".$rows[$i]["id"]);
		}
		
	}
	
	function getFunctionContent()
	{
		$menu_code=$_REQUEST["menu_code"];
		$sql="select content from sys_function where no='$menu_code'";
		echo Tool::get1bysql($sql);
	}
	
	//根据模型创建数据库表
	function createTableByModel($modelid,$delete_before_create)
	{
		//$modelid=$_REQUEST["modelid"];
		import("@.Action.System.ModelAction");
		$engine=new ModelAction();
		$model=$engine->getGoodModel($modelid);
		$tablename=$model["tablename"];
		$m=new Model();
		if ($delete_before_create=='true')
		{
			$m->execute("drop table if exists $tablename");
		}
		$sql="select * from $tablename limit 1,1";
		//System::log($sql);
		$rows=$m->query($sql);
		$r="失败";
	if (mysql_errno()==1146) //表不存在
			$r=$this->createNewTableByModel($model);//创建新表
		else
			$r=$this->UpdateTableByModel($model);//更新表结构

		return $r;
	}
	function createNewTableByModel($model)
	{
		$fields=$model["fields"];
		$all_fields="";
		foreach($fields as $field)
		{
			if (!$field["virtualfield"] && $field["type"]!="calculated")//不是虚拟字段，也不是表达式字段
			{
				$f=$this->get_field_sql($field);
				
				if ($all_fields)
					$all_fields.=",";
				$all_fields.=$f;	
			}
			
		}
		$tablename=$model["tablename"];
		$sql="create table $tablename ($all_fields) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
		System::log("建表:".$sql);
		
		//return $sql;return; 
		$m=new Model();
		$m->execute($sql);
		return mysql_error();
		
		
	}
	function UpdateTableByModel($model)
	{
		$table=$model["tablename"];
		$sql="SELECT column_name, column_comment FROM information_schema.columns WHERE table_name = '$table'";
		$m=new Model();
		$existed_fields=$m->query($sql);
		
		$fields=$model["fields"];
		$all_fields="";
		foreach($fields as $field)
		{
			if (!$field["virtualfield"] && $field["type"]!="calculated")//不是虚拟字段，也不是表达式字段
			{
				$f_id=$field["id"];
				
				if (!Tool::array_key_exist($existed_fields,"column_name",$f_id))
				{
					$f=$this->get_field_sql($field);				
					if ($all_fields)
						$all_fields.=",";
					$all_fields.="add ".$f;	
				}
			}
			
		}
		$tablename=$model["tablename"];
		$sql="alter table $tablename $all_fields";
		System::log("更新表:".$sql);
		
		//return $sql;return; 
		$m=new Model();
		$m->execute($sql);
		return mysql_error();
	}
	
	//根据模型字段属性，转化为创建表需要的字段的sql语句
	function get_field_sql($field)
	{
	
		$f_type=$field["fieldtype"];
		if ($f_type=="string")
			$f_type="varchar";
		$f_length=$field["length"];
		if (!$f_type)
		{
			$f_type="varchar";
			if (!$f_length)
				$f_length="50";
		}
		if ($f_type=="varchar" || $f_type=="char")
		{
			if (!$f_length)
				$f_length="50";
		}
		
		$f_id=$field["id"];
		$f="$f_id $f_type ";
		if ($f_length)
			$f.=" ($f_length)";
		if ($field["nullable"]=="false")
		{
			$f.=" NOT NULL ";
		}
		else
			$f.=" NULL ";
		$prop=$field["prop"];
		if (strpos($prop,"PRIMARYKEY")>-1)
		{
			$f.=" PRIMARY KEY ";
			if (strpos($prop,"AUTO_INCREMENT")>-1)
			{
				$f.=" AUTO_INCREMENT";
			}
		}
	
		$f_notes=$field["name"];
		$f.=" COMMENT '$f_notes' ";
		
		return $f;
	}
	
	function test1()
	{
			$m=new Model();
			$sql="create table atest1 (id int  (10) NULL   KEY  AUTO_INCREMENT COMMENT 'ID1' )";
		$n=$m->execute($sql);
		echo $err=mysql_error();
	}
	//根据节点id找到员工
	function getStaffByNodeid()
	{
		$id=$_REQUEST['id'];
		$m=new Model();
		$sql="select * from sys_staff where id =$id ";//.$data[0]['staffid'];
		$array=$m->query($sql);
		$sql="select name from sys_dept where id = ".$array[0]['deptid'];
		$array2=$m->query($sql);
		$array[0]['deptid']=$array2[0]['name'];
		echo json_encode($array);
	}
	
	
	function  menutree()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}

	function  columntree()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}
	
	
	
	function getcolumn()
	{
		$table=$_REQUEST["table"];
		//echo $table;
		//exit;
		$id=$_REQUEST["id"];
		$pid=$_REQUEST["pid"];
		$name=$_REQUEST["name"];
		$id=$_REQUEST["id"];
		$type=$_REQUEST["type"];
		$filter=$_REQUEST["filter"];
		$data=null;
		$sql="select $name,$id,$pid";
		$otherfield=$_REQUEST["otherfield"];
		if ($otherfield)
			$sql.=",".$otherfield;
		if ($type)
			$sql.=",$type ";
		$sql.=" from $table ";
		if ($filter)
			$sql.=" where (".$filter.")";
	
		if (isset($_REQUEST["orderby"]))
			$sql.=" order by ".$_REQUEST["orderby"];
		$sql=Expression::parseExpression($sql);
		System::log($sql);
		$m=new Model();
		$data=$m->query($sql);
		echo json_encode($data);
	}
	
	function  treemanager()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->assign("mcss_lang",C("mcss_lang"));
		$this->display();
	}
	function renameModel()
	{
		$modelid=$_REQUEST["modelid"];
		$newid=$_REQUEST["newid"];
		$filename=$this->getModelPathByModelid($modelid);
		$newname=$this->getModelPathByModelid($newid);
		if (rename($filename,$newname))
			echo "ok";
		else
		{
			System::log("修改文件名失败：".$filename.' 到 '.$newname);
			echo "修改失败";
		}
	}
	
	function getModelid()
	{
		$menuid=$_REQUEST["id"];
		$sql="SELECT modelid FROM `ems_menu` where id=$menuid";
		$m=new Model();
		$data=$m->query($sql);
		echo json_encode($data);
		
	}
	
	//生成菜单的sql语句，用户菜单编辑器
	function getFunctionDataList()
	{

		$funcCodes=$_REQUEST["funcCodes"];
		$funcCodes=explode(",",$funcCodes);
		$s="";
		for($i=0;$i<count($funcCodes);$i++)
		{
			if ($s) $s.=",";
			$s.="'".$funcCodes[$i]."'";
		}
		echo Data::getDataJSON("select * from sys_function where no in ($s)");
	}
	//获取到从sql传来的数据
	function getSmartSelectList(){
		$sql = $_REQUEST["sql"];
		$page = ($_REQUEST["page"]-1) * 10;
		if($page<0)
			$page = 0;
		$condition = $_REQUEST["condition"];
		if(strpos($sql,"sql:")>-1){//获取到前台传过来的sql语句
			$arr = explode(":",$sql);
			$sql = Expression::parseExpression($arr[1]);
		}
		$m = new Model();
		$onesql = "select a.* from (".$sql.") a limit 0,1";
		$data = $m->query($onesql);
		$i = 0;
		foreach($data[0] as $key=>$value){
			if($i == 0)
				$firstField = $key;
			$field = $key;
			$i++;
		}
		if($condition!="")
			$selsql = "select a.* from (".$sql.") a where a.$field like '%$condition%' limit $page,10";//获取十条数据
		else
			$selsql = "select a.* from (".$sql.") a limit $page,10";
		$data = $m->query($selsql);
		if(count($data)>0){
			$data[0]['firstField'] = $firstField;
			$data[0]['secondField'] = $field;
			if($condition!="")
				$sql = "select count(a.$field) from (".$sql.") a where a.$field like '%$condition%'";
			else
				$sql = "select count(a.$field) from (".$sql.") a";
			$total = Tool::get1bysql($sql);
			if(($total%10)>0)
				$total=((int)($total/10))+1;
			else
				$total=(int)($total/10);
			$data[0]['totalpage'] = $total;
		}
		echo json_encode($data);
	}
	//判断用户输入的值是否存在与数据库中
	function accessIsHave(){
		$sql = $_REQUEST["sql"];
		$condition = $_REQUEST["condition"];
		if(strpos($sql,"sql:")>-1){//获取到前台传过来的sql语句
			$arr = explode(":",$sql);
			$sql = Expression::parseExpression($arr[1]);
		}
		$m = new Model();
		$onesql = "select a.* from (".$sql.") a limit 0,1";
		$data = $m->query($onesql);
		$i = 0;
		foreach($data[0] as $key=>$value){
			if($i == 0)
				$firstField = $key;
			$field = $key;
			$i++;
		}
		if($condition!="")
			$sql = "select a.* from (".$sql.") a where a.$field like '%$condition%'";//获取十条数据
		else
			$sql = "select a.* from (".$sql.") a";
		$data = $m->query($sql);
		if(count($data)==0)
			echo 0;
		else 
			echo $data[0][$firstField];
	}

}
?>