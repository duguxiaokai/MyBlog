<?php
/*
这个类是表达式类
作者：陈坤极
*/
Class Expression extends Model{

	//解析各种表达式，解析时要知道解析器所在的类，方法，返回值类型，返回值是否多个值
	static public function parseExpression($exprestionStr)
	{
		$exp=array(
			"newid()"=>array("System","newid","string"),
			"randstr()"=>array("System","randstr","string"),
			"loginuser()"=>array("System","loginuser","string"),
			"loginuser_noyinhao()"=>array("System","loginuser"),//_noyinhao表示替换为解析后的值后无需加引号。只要把第三个参数改为不是string，就不会加引号
			"getcustomerid()"=>array("System","getcustomerid","string"),
			"loginuserid()"=>array("System","loginuserid","int"),
			"loginusername()"=>array("System","loginusername","sring"),
			"loginuseremail()"=>array("System","loginuseremail","sring"),
			"loginuserrole()"=>array("System","loginuserrole","sring"),
			"orgid()"=>array("System","orgid","int"),
			"orgname()"=>array("System","orgname","int"),
			"loginuser_customer_id()"=>array("System","loginuser_customer_id","int"),
			"now()"=>array("Expression","now","sring"),
			"{yyyy}"=>array("Expression","yyyy","sring"),
			"{yy}"=>array("Expression","yy","sring"),
			"{mm}"=>array("Expression","mm","sring"),
			"{dd}"=>array("Expression","dd","sring"),
			"today()"=>array("Expression","today","sring"),
			"siteid()"=>array("System","siteid","sring"),
			
			"Me()"=>array("Organization","MyStaffID","int"),
			"MyStaffID()"=>array("Organization","MyStaffID","int"),
			"MyStaffName()"=>array("Organization","MyStaffName","string"),
			"MyDeptId_df()"=>array("Organization","MyDeptId_df","int"),
			"MyDeptName_df()"=>array("Organization","MyDeptName_df","string"),
			
			"MyDeptStaffs()"=>array("Organization","MyDeptStaffs","int","multi"),
			"MyDeptStaffs_df()"=>array("Organization","MyDeptStaffs_df","int","multi"),
			"MyDeptAndSubDeptIDs_df()"=>array("Organization","MyDeptAndSubDeptIDs_df","int","multi"),
			
			"StaffPositions()"=>array("Organization","StaffPositions","sring","multi"),	
			
		);
		foreach($exp as $key=>$value){
			if(strpos($exprestionStr,$key)>-1)
			{
 				$newstr=call_user_func(array($value[0],$value[1]));
				if ($exprestionStr==$key){
					$exprestionStr=$newstr;
				}
				else{
					if ($value[3] && $value[3]=="multi"  && $value[2]=="sring") //处理解析后的返回值中包含多个值，用逗号分开的情形
					{
						$newstr=self::strToArrStr($newstr);
					}
					if ($value[2] && $value[2]=="string")
						$newstr="'".$newstr."'";
					$exprestionStr = str_replace($key,$newstr, $exprestionStr);
				}
			}  		
			
		}
		/*下面继续解析下列带参数的表达式，需要用正则表达式
		{newid('ORDER',4)}AAAAA {newNAME('FFF',4,555)
		例如，模型编辑器的字段默认值如果是：{newid('ORDER',4)}
		则解析为 ORDER-2013-03-29-0001\ORDER-2013-03-29-0002\ORDER-2013-03-29-0003
		解析newid()时调用System::newid()方法，带允许带参数
		*/
		preg_match_all("/\{\S*\}/", $exprestionStr,$match);
		if (count($match[0])>0)
		{
			foreach($match[0] as $onematch)
			{
				$oldstr=$onematch;
		 		$onematch=substr($onematch,1,strlen($onematch)-2);		
				$funcName=substr($onematch,0,strpos($onematch,"("));
				$params=substr($onematch,strpos($onematch,"(")+1);
				$params=substr($params,0,strpos($params)-1);
				$params=explode(',',$params);
				if ($funcName=='newid')
				{
					$n=count($params);
					if ($n==1)
						$newstr=call_user_func("System::newid",$params[0]);
					else
					if ($n==2)
						$newstr=call_user_func("System::newid",$params[0],$params[1]);
					else
					if ($n==3)
						$newstr=call_user_func("System::newid",$params[0],$params[1],$params[2]);
					else
					if ($n==4)
						$newstr=call_user_func("System::newid",$params[0],$params[1],$params[3],$params[4]);
				}	
				if ($onematch!=$newstr)
				{
					$exprestionStr = str_replace($oldstr,$newstr,$exprestionStr);				
				}
			}
		
		}
		
		return $exprestionStr;
	}
	
	//把用逗号分开的字符串转化为给每节字符加上单引号的字符串。例如，把“ a,b,c ”转化为“ ‘a’,‘b’,‘c’ ” 
	static function strToArrStr($str)
	{
		$r="";
		$arr=explode(",",$str);
		foreach($arr as $v){
			if ($r!="")
				$r.=",";
			$r.="'".$v."'";
		}
		return $r;
	}
	
	static function now(){
		return date("Y-m-d H:i:s");
	}
	
	static function yyyy(){
		$date = explode('-',Expression::today());
		return $date[0];
	}
	static function yy(){
		$date = explode('-',Expression::today());
		return substr($date[0],2,2);
	}
	
	static function mm(){
		$date = explode('-',Expression::today());
		return $date[1];
	}
	
	static function dd(){
		$date = explode('-',Expression::today());
		return $date[2];
	}
	
	static function today(){
		return date("Y-m-d");
	}
			 
}
?>