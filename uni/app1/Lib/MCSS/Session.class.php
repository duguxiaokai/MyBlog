<?php
/*
��ϵͳ����Session�Ĵ�ȡ�����е�����ࡣ���ڻ�û����
*/
class Session
{
	//��¼�û���
	public static function set_loginuser($user) {
		$_SESSIONS["loginuser"]=$user;
	}
	public static function loginuser() {
		return $_SESSIONS["loginuser"];
	}
	
	//��ǰӦ�ã���֯�Ĵ��룩
	public static function set_app($appcode) {
		$_SESSIONS["mcss_app"]=$appcode;
	}
	public static function app() {
		return $_SESSIONS["mcss_app"];
	}
	//��ͬ��set_app
	public static function set_orgcode($orgcode) {
		$_SESSIONS["ORGCODE"]=$orgcode;
	}
	//��ͬ��app
	public static function orgcode() {
		return $_SESSIONS["ORGCODE"];
	}
	
	
 
}
?>