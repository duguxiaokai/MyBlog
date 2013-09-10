<?php
/*
把系统级的Session的存取都集中到这个类。现在还没用上
*/
class Session
{
	//登录用户名
	public static function set_loginuser($user) {
		$_SESSIONS["loginuser"]=$user;
	}
	public static function loginuser() {
		return $_SESSIONS["loginuser"];
	}
	
	//当前应用（组织的代码）
	public static function set_app($appcode) {
		$_SESSIONS["mcss_app"]=$appcode;
	}
	public static function app() {
		return $_SESSIONS["mcss_app"];
	}
	//等同于set_app
	public static function set_orgcode($orgcode) {
		$_SESSIONS["ORGCODE"]=$orgcode;
	}
	//等同于app
	public static function orgcode() {
		return $_SESSIONS["ORGCODE"];
	}
	
	
 
}
?>