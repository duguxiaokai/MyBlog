//alert("日历组件");

var McssCalendars=new Array;
function McssCalendar_getMcssCalendar(id){
	var r;
	for(var i=0;i<McssCalendars.length;i++){
		if (McssCalendars[i].id==id)
		{
			r = McssCalendars[i].data;
			break;
		}
	}
	return r;
}

/*
params.data:日期数据数组。格式：var data=[{2012-06-19:'我今天的工作1'},{2012-06-19:'我今天的工作2'},{2012-06-20:'我今天的工作3'},]
params.beforeShowData(this):显示当前月前的事件，通常用来更新数据，或显示本月的特殊信息
params.defaultDate:默认显示日期
params.mostRows:每天显示任务数上限
params.mostWords:条任务字数上限
params.refObject:引用该日历对象的其它对象，便于回调函数获得调用者对象的信息。例如，McssTable用到了McssCalendar，那么在使用时把本身对象作为参数传入。例如
calendar=new McssCalendar({id:mctable.tableid+"_view_calendar",refObject:mctable});
params.showCalendartype:是否显示视图选择
*/

function McssCalendar(params)
{
	McssCalendars.push({"id":params.id,data:this});
	if (!params.calendartype)
	{
		params.calendartype='week';
	}
	this.params=params;
	this.id=params.id;
	this.lang='cn';
	if (params.lang)
		this.lang=params.lang;
		
	this.css=params.css;
	//this.onclick=params.onclick;
	if (params.lang=="en")
		this.weekheader = "<th class='calendar_weekend_header'>Sun</th><th class='calendar_workday_header'>Mon</th><th class='calendar_workday_header'>Tue</th><th class='calendar_workday_header'>Wed</th><th class='calendar_workday_header'>Thu</th><th class='calendar_workday_header'>Fri</th><th class='calendar_weekend_header'>Sat</th>";
	else
		this.weekheader = "<th class='calendar_weekend_header'>日</th><th class='calendar_workday_header'>一</th><th class='calendar_workday_header'>二</th><th class='calendar_workday_header'>三</th><th class='calendar_workday_header'>四</th><th class='calendar_workday_header'>五</th><th class='calendar_weekend_header'>六</th>";
	
	this.rooturl=getrooturl();

	this.run=function(func){
		var _this=this;
		var defaultDate=this.newDate(this.getGoodDate(new Date()));
		if (this.params.defaultDate)
			defaultDate=this.params.defaultDate;
		this.changeCurrentDate(defaultDate);


		this.today=new Date();
		this.today_str=this.getGoodDate(this.today);
		this.defaultDate_str=this.getGoodDate(defaultDate);
		this.hidebutton=this.params.hidebutton;
		if (this.params.lang)
		//autoform_importJS("lang/"+this.lang+"/language.js");
		if (this.params.css)
		autoform_importJS(this.params.css);
		var html="";
		html+="<div id='"+this.params.id+"_header'>";
		
		html+="<div id='"+this.params.id+"_yearmonth' style='float:right'><a id='"+this.id+"_preyear' class='labellink'>&nbsp;</a><span id='"+this.id+"_year'>"+this.year+"</span><a id='"+this.id+"_nextyear' class='labellink'>&nbsp;</a><a id='"+this.id+"_premonth' class='labellink'>&nbsp;</a><span id='"+this.id+"_month'>"+this.month+"</span><a id='"+this.id+"_nextmonth' class='labellink'>&nbsp;</a></div>";
		
		html+="<div id='"+this.id+"_showdate' style='display:none'><a id='"+this.id+"_preday' class='labellink'> </a><input type='text' style='width:90px' id='"+this.id+"_currentdate' value='2012-01-01' /><a id='"+this.id+"_nextday' class='labellink'> </a></div>";
		html+="<div id='"+this.id+"_shituqiehuan'></div>";
		html+="</div>";
		
		html+="<table id='"+this.params.id+"_mcsscalendar' border='0' cellpadding='0' cellspacing='0' class='liststyl'></table>";
		/*
		if(!this.hidebutton){
		html+="<div class='rilibottomBut'><a href='#' title='我的日报'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton1.png'/></a><a href='#'  title='查看我下级人员工作报告'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton2.png'/></a><a href='#' title='工作报告列表'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton3.png'/></a><a href='#' title='工作报告视图'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton4.png'/></a><a href='#' title='一周的工作视图'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton7.gif'/></a><a href='#' title='一月的工作视图'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/rilibutton31.gif'/></a><a href='#'><img src='"+this.rooturl+"/Public/jusaas/calendar/images/add2.gif'/></a></div>";
		}
		*/
		
		$("#"+this.id).html(html);
		if (this.params.hideYearMonth)
		{
			$("#"+this.params.id+"_yearmonth").hide();		
		}
		if (this.params.showCalendartype)
		{	
			var temp="";
			//temp+="<span  class='shitubg' style='cursor:pointer' id='"+this.params.id+"_view_day'>日视图</span>-";
			temp+="<span class='shitubg shitubg_fouce' style='cursor:pointer' title='周视图' id='"+this.params.id+"_view_week' >周视图</span>";
			temp+="<span class='shitubg' title='月视图' style='cursor:pointer' id='"+this.params.id+"_view_monthweek' >月视图</span>";
			//temp+="<span  class='calendar_view'  style='cursor:pointer' id='"+this.params.id+"_view_month'>-月</span>";
			var _this=this;

			$("#"+this.id+"_shituqiehuan").html(temp);

			$(".shitubg").click(function(){
				calendar_changeview(this,_this); 
				$(this).addClass('shitubg_fouce').siblings().removeClass('shitubg_fouce');
			})
			
		}
		if (this.params.calendartype=="selectdate" || this.params.hidecurrentdate)
		{
			$("#"+this.id+"_showdate").hide();
		}
			
		$("#"+this.id+"_preyear").click(function(){
			//点击到上一年
			preYear(_this);
		});
		$("#"+this.id+"_nextyear").click(function(){
			//点击到下一年
			nextYear(_this);
		});
		$("#"+this.id+"_premonth").click(function(){
			//点击到上个月
			preMonth(_this);
		});
		$("#"+this.id+"_nextmonth").click(function(){
			//点击到下个月
			nextMonth(_this);
		});
		$("#"+this.id+"_preday").click(function(){
			//点击到上一天
			preday(_this);
		});
		$("#"+this.id+"_nextday").click(function(){
			//点击到下一天
			nextday(_this);
		});
		$("#"+this.id+"_currentdate").val(this.defaultDate_str).change(function(){
			//修改当前日期
			changedate(_this);
		});
		$("#"+this.id+"_currentdate").blur(function(){
			//修改当前日期
			//mcdom.closePopup();
		});
		
		
		this.refreshCalendarBody();
		if (this.params.afterRun)
			this.params.afterRun(this);
	}
	
	this.changeCurrentDate=function(date)
	{	
		this.year=date.getFullYear();
		this.month=date.getMonth()+1;
		this.currentdate=date;
		//if(this.params.onclick)
			//this.params.onclick(this.getGoodDate(date),this);

	}

	this.refreshCalendarBody=function()
	{
		this.refreshBeginEndDateForCurrentView();
		if (this.params.beforeShowData)
		{
			
			this.params.beforeShowData(this);
		}
		else
			this.showCalendarBody();
	}
	
	this.showCalendarBody=function()
	{
		var _this=this;
		$("#"+this.id+"_currentdate").val(this.getGoodDate(this.currentdate));
		if (this.params.calendartype!="selectdate")
			calendar_selectdate(this.id+'_currentdate',this);
		
		var h=this.getDateTD(this.year,this.month);

		$("#"+this.params.id+"_mcsscalendar").html(h);
		
		if (this.params.onshowMyWholePeriodWorkData)
		{
			var cellcount=$("#"+this.params.id+"_mcsscalendar").get(0).rows[0].cells.length;
			$("#"+this.id+"_wholeperiod_td").attr("colspan",cellcount);
		}
		//把默认日期格子突出
		$("td[title='"+this.getGoodDate(this.currentdate)+"']").addClass('ontoday_day');
		//把当天格子突出
		$("td[title='"+this.today_str+"']").addClass('ontoday').removeClass('ontoday_day');
		
		
		if (this.params.onclick)
		{
			var n=$(".date_td").length;
			//var n=$(".selectthis").length;
			for(var i=0;i<n;i++)
			{
				$(".date_td").eq(i).click(function(){
					if (this.id.indexOf(_this.id)==0)
						_this.params.onclick(this.title,_this);
				});

			}
			
		}
		$(".date_td").mouseover(function(){
			$(this).removeClass("workday_onmouseout"); 
			$(this).addClass("workday_onmouseover"); 
		});	
		$(".date_td").mouseout(function(){
			$(this).removeClass("workday_onmouseover"); 
			$(this).addClass("workday_onmouseout"); 
		});	
		this.BindAdd();
		if (this.params.afterShowCalendarBody)
			this.params.afterShowCalendarBody(this);
	}
	
	this.BindAdd=function()
	{
		var _self=this;
		$("#"+this.id).find(".plus").click(function(){
			var obj=this;
			if (_self.params.onAdd)
				_self.params.onAdd(_self,obj.parentNode.parentNode.title,obj);
		})
		$("#"+this.id).find(".moreplus").click(function(){
			var obj=this;
			if (_self.params.onAddMore)
				_self.params.onAddMore(_self,obj.parentNode.parentNode.title,obj);
		})
	
	}
	//创建某年某月的格子
	//calendartype:日历类型：月、周、日。目前只支持月
	this.getDateTD=function(year,month)
	{	
		var r = "";

		if (! this.params.calendartype || this.params.calendartype=='monthweek'  || this.params.calendartype=='selectdate')//月－周模式
		{
			r =this.getMonthWeekCalendar(year,month);
		}
		else
		if (this.params.calendartype=='month')//月模式
		{
			r =this.getMonthCalendar(year,month);
		}
		else
		if (this.params.calendartype=='weekinmonth')//月模式
		{
			r =this.getWeekInMonthCalendar(year,month);
		}
		else
		if (this.params.calendartype=='week')//周模式
		{
			r =this.getWeekCalendar();
		}
		else
		if (this.params.calendartype=='day')//周模式
		{
			r =this.getDayCalendar(year,month);
		}
		if (this.params.onshowMyWholePeriodWorkData)
		{
			r+="<tr><td id='"+this.id+"_wholeperiod_td'><p class='calendar_header_bg'>全周期任务</p>"+this.params.onshowMyWholePeriodWorkData(this,this.params.refObject)+"</td></tr>";
		}
		return r;
	}


	//创建月－周日历
	this.getMonthWeekCalendar=function(year,month)
	{
		var r = "";
		d1=this.newDate(year+"-"+(month)+"-1");

		w = d1.getDay();//本月第一天星期几
		var maxday=getDaysOfMonth(year,month);
		var weekdaylist = [0,1, 2, 3, 4, 5,6];
		var weeklist = [1, 2, 3, 4, 5];
		//if (w==5 ||	w==6 && maxday==31) //第一天是星期五或星期六，则本月跨6周
		if ((w==5&& maxday==31) ||(w==6 && (maxday==30||maxday==31)))
		{
			weeklist = [1, 2, 3, 4, 5,6];
		}else
		if (w==0 && maxday==28)
		{
			weeklist = [1, 2, 3, 4];
		}
		

		n = 0;
		r = "<tr>"+this.weekheader+"</tr>";
		for (var i=0;i < weeklist.length;i++) 
		{
			we=weeklist[i];
			
			r = r + "<tr>";
			for (var j=0;j < weekdaylist.length;j++) 
			{
				 var d=weekdaylist[j];

				 if (w==d && n==0)
				 {
					 n = 1;
				 }
				 else
				 if (n>0 && n<=maxday)
				 {
					 n = (n  +  1);
				 }
				 else
				 {
				 }
				 day=n;
				 if (n==0 || n>maxday) day="";
		 
				if (day!=""){
					r +=this.addCell(d1,day);
					d1.setDate(d1.getDate()+1);//日期增加一天
				} else
				   r = r + "<td></td>";
			}		
			r = r + "</tr>";
		}

		return r;
	},
	
	//创建一个月中的各周，每周占一格
	this.getWeekInMonthCalendar=function(year,month)
	{
		var year=this.year;
		var month=this.month;
		var r = "";
		d1=this.newDate(year+"-"+(month)+"-1");
		w = d1.getDay();//本月第一天星期几
		var maxday=getDaysOfMonth(year,month);
		var weeklistinfo = ['第一周','第二周','第三周','第四周','第五周'];
		if ((w==5&& maxday==31) ||(w==6 && (maxday==30||maxday==31)))
		{
			weeklistinfo = ['第一周','第二周','第三周','第四周','第五周','第六周'];
		}else
		if (w==0 && maxday==28)
		{
			weeklistinfo = ['第一周','第二周','第三周','第四周'];
		}
		
		r+="<tr id='monthweekly' len=\""+weeklistinfo.length+"\">";
		var wed=7-1-w;		
		var d1=1;//本周第一天
		var d2=0;//本周最后一天
		var dd='';
		for(var j=0;j<weeklistinfo.length;j++)
		{
			if(j==0&&w!=0)
			{
				d2=d1+wed;
				if(w!=0)
				{
					if(month==1)
					{
						var prevyear=year-1;
						var fristmonth=12;					
					}
					else
					{
						var prevyear=year;
						var fristmonth=month-1;	
					}					
					var prevmonthmaxday=(getDaysOfMonth(prevyear,fristmonth)-w+1);					
				}else
				{
					var fristmonth=month;
					var prevmonthmaxday=d1;
				}
				if(!dd)
					dd = new Date(prevyear, (fristmonth-1), prevmonthmaxday); 					
				r+="<th class='calendar_weekend_header begindate' begindate='"+dd+"' by=\""+prevyear+"\" ey=\""+year+"\" bm=\""+fristmonth+"\" em=\""+month+"\" bd=\""+prevmonthmaxday+"\" ed=\""+d2+"\">"+weeklistinfo[j]+"("+(fristmonth)+"."+prevmonthmaxday+"-"+month+"."+d2+")</th>";
			}else{				
				d1=d2+1;
				d2=d1+6;
				if(d2>maxday)
				{					
					if(month==12)
					{
						var lastmonth=1;
						var lastyear=year+1;
					}else
					{
						var lastmonth=month+1;
						var lastyear=year;
					}
					d2=d2-maxday;
				}else{
					var lastmonth=month;
					var lastyear=year;
				}
				if(!dd)
					dd = new Date(prevyear, (fristmonth-1), prevmonthmaxday); 					
				r+="<th class='calendar_weekend_header' by=\""+year+"\" ey=\""+lastyear+"\" bm=\""+month+"\" em=\""+lastmonth+"\" bd=\""+d1+"\" ed=\""+d2+"\">"+weeklistinfo[j]+"("+month+"."+d1+"-"+lastmonth+"."+d2+")</th>";	
			}					
		}
		r+="</tr><tr>";
		for(var j=0;j<weeklistinfo.length;j++)
		{
			var day=dd.getDate();//获得该日期是几号
			r +=this.addCell(dd,day);
			dd=dd.addDays(7); 
			
		}
		
		r+="</tr>";
		return r;	
	},
	
	//创建周日历
	this.getWeekCalendar=function()
	{
		var r = "";
		maxday = 7;
		var daylist = [1, 2, 3, 4, 5,6,7];
		r = "<tr>"+this.weekheader+"</tr>";

		var d1=this.currentdate;
		var weekday=d1.getDay();
		if (weekday!=0)//如果不是周日
			d1= d1.addDays(weekday*-1); 
		for(var weekday=1;weekday<=7;weekday++)
		{
			var day=d1.getDate();//获得该日期是几号
			r +=this.addCell(d1,day);
			d1=d1.addDays(1); 
			
		}
		return r;
		
	
	},	
	//计算当前视图（如周视图）的第一天、最后一天的日期
	this.refreshBeginEndDateForCurrentView=function()
	{
		if (this.params.calendartype=="day")
		{
			this.firstDate=this.currentdate;
			this.lastDate=this.currentdate;
		}
		else
		if (this.params.calendartype=="week")
		{
			var d1=this.currentdate;
			var weekday=d1.getDay();
			if (weekday!=0)//如果不是周日
				d1= d1.addDays(weekday*-1); 
			this.firstDate=d1;
			this.lastDate=d1.addDays(6); 
		}
		else
		if (this.params.calendartype=="monthweek" || this.params.calendartype=="month" || this.params.calendartype=="weekinmonth")
		{
			var m=parseInt(this.currentdate.getMonth())+1;
			this.firstDate=this.newDate(this.currentdate.getFullYear()+"-"+m+"-1");			
			var maxday=getDaysOfMonth(this.currentdate.getFullYear(),this.currentdate.getMonth());	
			this.lastDate=this.firstDate.addDays(maxday-1); 
		}		
	},
	 
	//创建一天日历
	this.getDayCalendar=function(year,month)
	{
		var r = "";
		var d1=this.currentdate;
		var day=d1.getDate();//获得该日期是几号
		var weekday=d1.getDay();
		var wd=calendar_weekday[weekday];
		if (weekday==6 || weekday==0)
			r+="<th class='calendar_weekend_header'>"+wd+"("+day+"号）</th>";
		else
			r+="<th class='calendar_workday_header'>"+wd+"("+day+"号）</th>";
			
		r = "<tr>"+r+"</tr>";
		r +="<tr>"+this.addCell(d1,"")+"</tr>";
		return r;
	
	},

	//创建月－周日历
	this.getMonthCalendar=function(year,month)
	{
		var r = "";
		var maxday=getDaysOfMonth(year,month);
		var days="";
		var d;//几号
		var wd=0;//周几
		for(var i=1;i<=maxday;i++)
		{
			d=this.newDate(year+"-"+(month-1)+"-"+i);
			wd=d.getDay();
			if (wd==6)
				days+="<th class='calendar_weekend_header'>"+i+"周六</th>";
			else if (wd==0)
				days+="<th class='calendar_weekend_header'>"+i+"周日</th>";
			else
				days+="<th class='calendar_workday_header'>"+i+"</th>";
		}
		r = "<tr>"+days+"</tr>";
		var d1=this.newDate(year+"-"+(month-1)+"-1");
		for(var day=1;day<=maxday;day++)
		{
			r +=this.addCell(d1,"");
			d1.setDate(d1.getDate()+1);//日期增加一天
			
		}
		return r;
		
	
	},

	/*
	d1:第一天的日期
	day:日，日期中的日，如1，6，31
	*/
	this.addCell=function(d1,day)
	{
		var day_data=day;
		if (this.params.onShowDayData)
		{
			day_data=this.params.onShowDayData(this,this.getGoodDate(d1),day,this.params.refObject);
		}
		else
		if (this.params.data)
		{
			day_data=this.getDayData(this.getGoodDate(d1),day,this.params.data);
		}
		htm = "<td id='"+this.id+"_day_"+day+"' class='date_td' title='"+this.getGoodDate(d1)+"'>"+day_data+"</td>";
		return htm;
	},
	
	this.getDayData=function(date,day,data)
	{
		if (this.params.showAdd)
			day+="<span style='cursor:pointer;' class='plus' title='添加'>";
		day+="</span>";
		var h="<div><span>"+day+"</span><span style='text-align:right;'></span></div>";
		var taskCount=0;
		for(var i=0;i<data.length;i++)
		{
			if (data[i].date==date)
			{
				if (taskCount<this.params.mostRows)
				{
					var temp=data[i].work;
					if (this.params.mostWords && data[i].work.length>this.params.mostWords)
					{
						temp=data[i].work.substr(0,this.params.mostWords)+"...";
					}
					h+=temp+"<br />";
					taskCount++;
				}
				else
				{
					h+="<span>更多...</span>";
					break;
				}

			}
		}		
		return h;
	},
	
	this.refreshAday=function(date)
	{
		var d1=this.newDate(date);
		var day=d1.getDate();
		var h=this.getDayData(date,day,this.params.data);
		$("#"+this.id+"_day_"+day).html(h);
		//alert("刷新"+date);
	}
	
	

	//把2011－10－12或2011／10／12格式的字符串转化为日期类型，兼容各种浏览器
	this.newDate=function(datestr)
	{
		var arr=datestr.split("-");
		if (arr.length<2)
		{
			arr=datestr.split("/");
		}
		var d=new Date("2012/1/1");
		d.setFullYear(arr[0]);
		d.setDate(arr[2]);
		d.setMonth(arr[1]-1);
		//d.setHour
		return d;
	}


	this.markSelected=function()
	{
		 $("td").each(function (i,td) {
			if (selectedDate.indexOf(td.title)==-1 || td.title=="")
			{
				td.style.cssText ="background-color:white;cursor:pointer;";
			}
			else
			{
				td.style.cssText ="background-color:red;cursor:pointer;";
			}
		});
	}
	
	//把日期类型的变量转化为字符串日期如2012-12-31
	this.getGoodDate=function(date1)
	{
		
		var r="";
		if(date1)
		{
			r=date1.getFullYear();
			var m=date1.getMonth()+1;
			var d=date1.getDate();
			if (m<10)r+="-0"+m;else r+="-"+m;
			if (d<10)r+="-0"+d;else r+="-"+d;
		}
		return r;		
	}

	this.selectThis=function(td)
	{
		if (mcdate.onclickDate)
		mcdate.onclickDate(td);
		return
		var Arr=selectedDate.split(",");

		if (selectedDate.indexOf(td.title)==-1)
		{
			Arr.push(td.title);
			td.style.cssText ="background-color:red;cursor:pointer;";	
		}
		else
		{
			for(i=0;i<Arr.length;i++)
			{
				if (Arr[i]==td.title)
				{
					Arr.shift(td.title);
					td.style.cssText ="background-color:white;cursor:pointer;";
				}
			}
		}
		selectedDate=Arr.join();
		if (selectedDate.substr(0,1)==",")
			selectedDate=selectedDate.substr(1,selectedDate.length);
	}
	//把外来的数据源转化为日历的数据源
	this.toData=function(data,timeField,titleField,keyfield)
	{
		var data=[];
		for(var i=0;i<data.length;i++)
		{
			if (data[i][timeField])
				data.push({date:data[i][timeField],work:data[i][titleField],id:data[i][keyfield]});
		}
		return data; 
	}
	
		//获得本周的开始日期     
	this.getWeekStartDate=function() 
	{   
		var nowDayOfWeek = this.currentdate.getDay();
		var nowDay=this.currentdate.getDate();
		var month=this.month-1;
		var weekStartDate = new Date(this.year, month, nowDay-nowDayOfWeek);      
		return this.getGoodDate(weekStartDate);     
	}      
		
	//获得本周的结束日期     
	this.getWeekEndDate=function() 
	{      
		var nowDayOfWeek = this.currentdate.getDay();
		var nowDay=this.currentdate.getDate();
		var month=this.month-1;
		var weekEndDate = new Date(this.year, month, nowDay + (6 - nowDayOfWeek));      
		return this.getGoodDate(weekEndDate);     
	}      
		
	//获得本月的开始日期     
	this.getMonthStartDate=function()
	{  
		var month=this.month-1;
		var monthStartDate = new Date(this.year,month, 1);      
		return this.getGoodDate(monthStartDate);     
	}     
		
	//获得本月的结束日期     
	this.getMonthEndDate=function()
	{     
		var month=this.month-1;
		var monthEndDate = new Date(this.year,month,this.getMonthDays(month));     
		return this.getGoodDate(monthEndDate);     
	} 
	//获得某月的天数
	this.getMonthDays=function(myMonth)
	{     
		var monthStartDate = new Date(this.year, myMonth, 1);      
		var monthEndDate = new Date(this.year, myMonth + 1, 1);      
		var days = (monthEndDate - monthStartDate)/(1000*60*60*24);      
		return days;      
	}  
	
}

McssCalendar.prototype.test=function(a)
{
	alert(a);
}
//刷新年和月
function refreshYearMonth(defaultDate,cal)
{
	cal.year=defaultDate.getFullYear();
	cal.month=defaultDate.getMonth()+1;
	cal.currentdate=defaultDate;
	$("#"+cal.id+"_year").html(cal.year);
	$("#"+cal.id+"_month").html(cal.month);

}
function preYear(cal)//
{
	cal.year=cal.year-1;
	cal.currentdate=cal.newDate(cal.year+"-"+(cal.month)+"-1");
	refreshYearMonth(cal.currentdate,cal);
	cal.refreshCalendarBody();
	cal.changeCurrentDate(cal.currentdate);
}
function nextYear(cal)
{
	cal.year=cal.year+1;
	cal.currentdate=cal.newDate(cal.year+"-"+(cal.month)+"-1");
	refreshYearMonth(cal.currentdate,cal);
	cal.refreshCalendarBody();
	cal.changeCurrentDate(cal.currentdate);
}

function preMonth(cal)
{
	cal.currentdate=cal.newDate(cal.year+"-"+(cal.month-1)+"-1");
	refreshYearMonth(cal.currentdate,cal);
	cal.refreshCalendarBody();
	cal.changeCurrentDate(cal.currentdate);
}

function nextMonth(cal)
{
	cal.currentdate=cal.newDate(cal.year+"-"+(cal.month+1)+"-1");
	refreshYearMonth(cal.currentdate,cal);
	cal.refreshCalendarBody();
	cal.changeCurrentDate(cal.currentdate);
}
function preday(cal)
{
	var d=$("#"+cal.id+"_currentdate").val();
	d = new Date(Date.parse(d)); 
	d=d.addDays(-1);
	if (cal.params.calendartype=="week")
		d=d.addDays(-7);
	$("#"+cal.id+"_currentdate").val(cal.getGoodDate(d));
	cal.currentdate=new Date(Date.parse(d)); 
	refreshYearMonth(cal.currentdate,cal);
	cal.refreshCalendarBody();
}
function nextday(cal)
{
	var d=$("#"+cal.id+"_currentdate").val();
	d = new Date(Date.parse(d)); 
	d=d.addDays(1);
	if (cal.params.calendartype=="week")
		d=d.addDays(7);
	$("#"+cal.id+"_currentdate").val(cal.getGoodDate(d));
	cal.currentdate=new Date(Date.parse(d)); 
	refreshYearMonth(cal.currentdate,cal);	
	cal.refreshCalendarBody();
}

function changedate(calendar)
{
	var d=$("#"+calendar.id+"_currentdate").val();
	calendar.currentdate = new Date(Date.parse(d)); 
	refreshYearMonth(calendar.currentdate,calendar)	;
	calendar.refreshCalendarBody();
}

function autoform_importJS(jsfile)
{
	var oHead = document.getElementsByTagName('HEAD').item(0);
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	var js=getrooturl()+"/Public/"+jsfile;
	oScript.src=js;
	oHead.appendChild( oScript);
}
function getrooturl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	var prePath=strFullPath.substring(0,pos);
	var postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1);
	var r=prePath+postPath;
	if (r.indexOf('index.php')>-1)
	{
		r=r.substr(0,r.length-9);
	}
 	return r;	
}

//是否闰年
function isLeapYear(year)
{
	return (year%4==0 && year%100!=0 || year%100==0 && year%400==0);
}

//获得指定年月的天数
function getDaysOfMonth(year,month)
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

//给日期加天数
Date.prototype.addDays = function(days) 
{
	return new Date(Date.parse(this) + (86400000 * days)); 

}

function calendar_selectdate(obj,cal)
{
	if (typeof(obj)=="string")
		obj=document.getElementById(obj);

	$(obj).click(function(){
		//$("#mcss_calendar_selectdate").remove();		
		//mcdom.closePopup(obj);
		//$(obj).after("<div id='mcss_calendar_selectdate' class='fuchuceng'></div>");
		var popup=mcdom.showPopup(this,"<div id='mcss_calendar_selectdate'></div>",null,null,null,null,180,200,null);
		var mccalendar=new McssCalendar({id:'mcss_calendar_selectdate',onclick:opendate,calendartype:'selectdate',refObject:cal});
		mccalendar.run();
	})

	function opendate(date,cal)
	{
		obj.value=date;
		changedate(cal.params.refObject);
		mcdom.closePopup(obj);
		//$("#mcss_calendar_selectdate").remove();
		//$(cal).css("display","none");

	}
}

var calendar_weekday={
"1":"周一",
"2":"周二",
"3":"周三",
"4":"周四",
"5":"周五",
"6":"周六",
"0":"周日"
}

function calendar_changeview(obj,calendar)
{
	var view="";
	if (obj.id==calendar.id+"_view_day") view="day";
	else if (obj.id==calendar.id+"_view_week")  view="week";
	else if (obj.id==calendar.id+"_view_monthweek")  view="monthweek";
	else if (obj.id==calendar.id+"_view_month")  view="month";
	calendar.params.calendartype=view;
	calendar.refreshCalendarBody();

		
}