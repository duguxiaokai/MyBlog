<?php

header("content-type:text/html; charset=UTF-8"); 
import("@.MCSS.System");
import("@.MCSS.MCSSModel");

class AdminAction extends Action{
	public function index() {
        header("Content-Type:text/html; charset=utf-8");
		if(!((isset($_SESSION['loginuser']) && ($_SESSION['loginuser'] != null )))){
			$url=__APP__.'/Meimeng/Admin/login';
			redirect($url,0,'');
			return;
		}
		$appname=$_SESSION["ORGCODE"];
		$role = $_SESSION["role"];
		$this->assign('mcss_app',$appname);
		$this->display();
	}
	//认证审核
	function check(){
		$id	  = $_REQUEST['userid'];
		$m    = new Model();
		$sql  = "update recruitment_user set ischeck='1' where id='$id'";
		//System::log($sql);
		$data = $m->execute($sql);
		if($data>0){
			echo "1";
		}
		else{
			echo "0";
		}
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
					$value['data']="sql:select name from sys_item where type='".$datavalue[1]."' and SYS_ORGID=$orgid";
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
	
	//新增数据
	function addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall,$id){
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
		$sql     = "insert into $tablename($fieldsql,jobid)values($fieldsqlvalue,'".$id."')";
		$result  = $m->execute($sql);
		System::log('rui'.$sql);
		//新增的兼职人员id
		$last_id = mysql_insert_id();
		//获取之前该活动信息的兼职人员staff_id
		$staffid = Tool::get1bysql("select staff_id from recruitment_jobs where id='".$id."'");
		//修改该活动信息的兼职人员
		$s       = "update recruitment_jobs set staff_id='".$staffid.$last_id.",' where id='".$id."'";
		$q       = $m->execute($s);
		System::log('liurui'.$s);
		System::log('id'.$id);
		$this->errorslist[$i]['row']=$i+2;
		$this->errorslist[$i]['errorinfo']=$m->getDbError();
		$this->errorslist[$i]['datainfo']=$data[$i];
		return $result;
	}
	
	public $errorslist = array();  //（导入）
	
	//读取xls导入数据库方法
	function xlsTosql()
	{
		$id = $_REQUEST['id'];
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
				$result = $this->addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall,$id);//调用新增数据的方法
				if($result==0)
					$inserterrors++;
				else
					$inserttrueth++;
			}else{     //当重复字段有值时进行两种方式的处理：忽略或者覆盖
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
					$result = $this->addNewRow($fieldvalue,$data,$fieldsql,$tablename,$i,$fieldsyshas,$fieldsysall,$id);//调用新增数据的方法
					if($result==0)
						$inserterrors++;
					else
						$inserttrueth++;
				}
			}	
			System::log('xiaorui'.$sql);
		}
		$this->errorslist[count($this->errorslist)] = $sheetname.'|'.count($data).'|'.$inserttrueth.'|'.$inserterrors.'|'.$ingorenum.'|'.$putnum;
		echo json_encode($this->errorslist);
	}
	//关闭活动
	function closeJob(){
		$id = $_REQUEST['id'];
		$m  = new Model();
		$sql = "update recruitment_jobs set state = '已结束' where id='".$id."'";
		$result = $m->execute($sql);
		echo $result;
		System::log($sql.'and'.$result);
	}
}
?>