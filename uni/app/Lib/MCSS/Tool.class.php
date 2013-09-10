<?php
/*
这个类中关于数据的操作都移动到了Data类中了，以后不要再使用这个类来操作数据
只保留非数据的方法
*/
class Tool extends Model
{
	public static function gotourl($appurl)
	{	
		if (strpos($appurl,"http://")===0 || strpos($appurl,"https://")===0 )
		{
			$url=$appurl;
		}
		else
		{
			$h='http';
			if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
				$h="https";
			$url=$h."://".$_SERVER['HTTP_HOST'].__APP__.'/'.$appurl;
		}
		header('Location:'.$url);	
	}
	
	public static function getPublicUrl()
	{
		$h='http';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
			$h="https";
		$app=__APP__;
		$app=substr($app,0,strpos($app,'index.php'));
		return $url=$h."://".$_SERVER['HTTP_HOST'].$app.'Public/';
	}
	
	public static function array_key_exist($arr,$keyname,$keyvalue)
	{
		foreach($arr as $one)
		{
			if ($one[$keyname]==$keyvalue)
			{
				return true;
			}
		}
		return false;
	}

	//下面都是关于数据的操作，已经移动到Data类
	public static function get1bysql($sql) {
		
		$m=new Model();
		$rows=$m->query($sql);
		if (count($rows)>0)
		{
			foreach($rows[0] as $key=>$value)
			{
				//Log::write($value.":".$sql);
				return $value;
			}
		}
		return null;
	}
	public static function sql($sql) {
		return Tool::executesql($sql);
	}
	public static function executesql($sql) {

		$m=new Model();
		$n=$m->execute($sql);
		if ($n>0)
			return $n;
		else{
			$err=mysql_error();
			if ($err)
				return mysql_errno().":".$err;
			else
				return mysql_errno();	
		}
			
	}	
	public static function get_mysql_error() {
		return mysql_errno().":".mysql_error();
	}
	public static function parse_error($err) {
		$r=$err;
		if (strpos($err,'Duplicate entry')>-1)
		{
			$r="输入数据重复。";
			Log::write($err);
		}
		return $r;
	}
	public static function get_my_error() {
		return Tool::parse_error(Tool::get_mysql_error());
	}
	public static function getDataJSON($sql)
	{
		$m=new Model();
		$rows=$m->query($sql);
		return json_encode($rows);
	}	
	//处理引号
	public static function dealSpecialChar($str)
	{
	    $repl = array("<yh>"=>"'",'\"'=>"'","\'"=>"'");
        return $str=strtr($str,$repl);
	}
	
}
?>