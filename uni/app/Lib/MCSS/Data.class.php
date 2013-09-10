<?php 
/*
数据操作类
作者：陈坤极
注意：这个类的静态方法不能与Model类的非静态方法同名，因为Data类继承了Model类
*/ 
class Data extends Model
{
	//查询符合条件的第一条记录的第一个字段的值
	public static function sql1($sql) {
		$m=new Model();
		$rows=$m->query($sql);
		if (count($rows)>0)
		{
			foreach($rows[0] as $key=>$value)
			{
				return $value;
			}
		}
		return null;		
	}
	//与sql1等效
	public static function get1bysql($sql) {
		
		$m=new Model();
		$rows=$m->query($sql);
		if (count($rows)>0)
		{
			foreach($rows[0] as $key=>$value)
			{
				System::log($value.$sql);
				return $value;
			}
		}
		return null;	
	}
	//查询获得记录数组
	public static function getRows($sql) {
		$m=new Model();
		return $m->query($sql);
	}
	/*
	返回值：大于零，则成功；等于0，则没有更新；否则，返回错误信息
	*/
	public static function sql($sql) {
		$m=new Model();
		$n=$m->execute($sql);
		
		if ($n>0)
			return $n;
			
		$err=mysql_error();
		if ($err)
			return 'err:'.mysql_errno().":".$err;
		return 0;
	}

	//执行sql，返回收影响的记录数量
	public static function executesql($sql) {
		return self::sql($sql);
	}	
	//返回最新的sql错误代码
	public static function get_mysql_error() {
		return mysql_errno().":".mysql_error();
	}
	//返回指定错误代码对应中文错误信息，该方法需要完善，需要对每个代码进行转换
	public static function parse_error($err) {
		$r=$err;
		if (strpos($err,'Duplicate entry')>-1)
		{
			$r="输入数据重复。";
			//Log::write($err);
		}
		return $r;
	}
	//返回最新的sql错误信息对应的中文信息
	public static function get_last_error() {
		return Data::parse_error(Tool::get_mysql_error());
	}
	//根据sql获得记录数组并转化为json给前台的getJSON方法
	public static function getDataJSON($sql)
	{
		$m=new Model();
		$rows=$m->query($sql);
		return json_encode($rows);
	}	
 	//同getDataJSON
	public static function json($sql)
	{
		$m=new Model();
		$rows=$m->query($sql);
		return json_encode($rows);
	}
	//把name<=>橘子~|~height<=>167cm<|>name<=>海风~|~height<=>162cm转化为多维数组
	public static function mcssStrToArray($arrStr)
	{
		$rows=explode('<|>',$arrStr);
		for($i=0;$i<count($rows);$i++)
		{
			if($rows[$i]!="")
			{
				$fields = explode('~|~',$rows[$i]);
				foreach($fields as $item)
				{
					if ($item)
					{
						$onefield=explode('<=>',$item);
						$arr[$i][$onefield[0]]=$onefield[1];
					}
				}
			}
		}
		return $arr;
	}		

}
 
?>