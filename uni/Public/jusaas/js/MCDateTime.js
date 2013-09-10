//alert("MCDateTime.js");
g_dinamic_cssjs.push("jusaas/js/MCDateTime.js");

var MCDateTime=new Object();

MCDateTime.getGoodTime=function(seconds)
{
	var need_hour=MCDateTime.changeTwoDecimal(parseFloat(seconds/3600));
	
	var need_minutes=MCDateTime.changeTwoDecimal(parseFloat(seconds/60));
	var need_time=seconds+'秒';
	if (need_hour>1)
		need_time=need_hour+'小时';
	else
	if (need_minutes>1)
		need_time=need_minutes+'分钟';
	return need_time;
 
}

//保留两位小数点后位数
MCDateTime.changeTwoDecimal=function(x)
{
	var f_x = parseFloat(x);
	if (isNaN(f_x))
	{
	//alert('function:changeTwoDecimal->parameter error');
	return false;
	}
	var f_x = Math.round(x*100)/100;

	return f_x;
}

MCDateTime.getGoodDate=function(date1)
{
	var r="";
	r=date1.getFullYear();
	var m=date1.getMonth()+1;
	var d=date1.getDate();
	if (m<10)r+="-0"+m;else r+="-"+m;
	if (d<10)r+="-0"+d;else r+="-"+d;
	return r;
}
	
function getdatetime() {
	var newnote=$("#newnote").val();
	var notes=$("#notes").val();
	var d = new Date()
	var vYear = d.getFullYear()
	var vMon = d.getMonth() + 1
	var vDay = d.getDate()
	var h = d.getHours();
	var m = d.getMinutes();
	var se = d.getSeconds();
	s=vYear+"-"+(vMon<10 ? "0" + vMon : vMon)+"-"+(vDay<10 ? "0"+ vDay : vDay)+" "+(h<10 ? "0"+ h : h)+":"+(m<10 ? "0" + m : m);
	return s;
}

MCDateTime.getMonthdays=function(year,month)
{
	var maxday = 31;
	if ((((month  ==  "4")  ||  (month  ==  "6"))  ||  (month  ==  "9"))  ||  (month  ==  "11"))
	{
		maxday = 30;
	}
	if (month  ==  "2")
	{
		if (isLeapYear(year))//闰年
			maxday = 29;
		else
			maxday = 28;
	}
	return maxday;
}

//把2011－10－12或2011／10／12格式的字符串转化为日期类型，兼容各种浏览器
MCDateTime.newDate=function(datestr)
{
	if (!datestr)
		return null;
	var arr=datestr.split("-");
	if (arr.length<2)
	{
		arr=datestr.split("/");
	}
	
	var d=new Date("");
	d.setFullYear(arr[0]);
	var m=arr[1];
	if (!m) return;
	if (m.substr(0,1)=="0")
		m=m.substr(1);
	m=parseInt(m);
	m--;
	d.setMonth(m);
	d.setDate(arr[2]);
	return d;
}


MCDateTime.calendar_weekday={
"1":"一",
"2":"二",
"3":"三",
"4":"四",
"5":"五",
"6":"六",
"0":"日"
}



//给日期加天数
Date.prototype.addDays = function(days) 
{
	return new Date(Date.parse(this) + (86400000 * days)); 
}
//两个日期相差的天数
MCDateTime.diffDays = function(begindate,enddate) 
{
	return (enddate-begindate)/3600000/24;
}