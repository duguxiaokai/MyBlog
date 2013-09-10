// JavaScript Document
function UpdateClocks(){
	//当地时间
	d = new Date();
	localTime = d.getTime();
	//中国格式
	var cn_year = d.getFullYear() ;
	var cn_month = d.getMonth() + 1 ;
	var cn_day = d.getDate() ;
	var cn_hour = d.getHours() ;
	var cn_minute = d.getMinutes() ;
	var cn_second = d.getSeconds() ;
	var cn_wenhou;
	if(cn_hour < 11 && cn_hour >= 0){
		cn_wenhou="早上好";
	}
	if(cn_hour < 13 && cn_hour >= 11){
		cn_wenhou="中午好";
	}
	if(cn_hour < 18 && cn_hour >= 13){
		cn_wenhou="下午好";
	}
	if(cn_hour < 24 && cn_hour >= 18){
		cn_wenhou="晚上好";
	}
	
		//中国月份
		if(cn_month==1) cn_months="01"
		if(cn_month==2) cn_months="02"
		if(cn_month==3) cn_months="03"
		if(cn_month==4) cn_months="04"
		if(cn_month==5) cn_months="05"
		if(cn_month==6) cn_months="06"
		if(cn_month==7) cn_months="07"
		if(cn_month==8) cn_months="08"
		if(cn_month==9) cn_months="09"
		if(cn_month==10) cn_months="10"
		if(cn_month==11)cn_months="11"
		if(cn_month==12)cn_months="12"
		//星期
		var week_cn;
			if(d.getDay()==0) week_cn="周日"
			if(d.getDay()==1) week_cn="周一"
			if(d.getDay()==2) week_cn="周二"
			if(d.getDay()==3) week_cn="周三"
			if(d.getDay()==4) week_cn="周四"
			if(d.getDay()==5) week_cn="周五"
			if(d.getDay()==6) week_cn="周六"
	//var Chinatime="Beijing in " +week_cn+','+ cn_months + cn_day +","+ cn_year +','+ cn_hour +":"+ cn_minute + ":" + cn_second;
	//输出
	var day_month=cn_months+"."+cn_day;
	document.getElementById("week_cn").innerHTML=week_cn;
	document.getElementById("day_cn").innerHTML=day_month;
	document.getElementById("wenhouyu").innerHTML=cn_wenhou;
}
	timerID = window.setTimeout("UpdateClocks()", 1001);
