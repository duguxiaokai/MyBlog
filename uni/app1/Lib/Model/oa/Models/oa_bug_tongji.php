<?php return array(
'id'=>"oa_bug_tongji",
'title'=>"Bug列表",
'tablename'=>"oa_bug",
'sql'=>"select b.username,
b.name,
(select count(*) from oa_bug where assign_to=a.name and date(SYS_ADDTIME) = curdate())
 as todaybug,
 
(select count(*) from oa_bug where SYS_ADDUSER=a.name and date(SYS_ADDTIME) = curdate() 
and status='1') as todayaddbug,
 
(select count(*) from oa_bug where (status='2' or status='7') and assign_to=a.name and 
date(SYS_EDITTIME) = date_sub(curdate(),INTERVAL 1 DAY)) as yesterdaybug,

(select count(*) from oa_bug where (status='2') and SYS_EDITUSER=a.name and 
date(SYS_EDITTIME) = curdate()) as today_completebug,

(select count(*) from oa_bug where assign_to=a.name and status='1') as shengyu_num 
from sys_user a 
left join sys_staff b on b.username=a.name
where  a.name in (select distinct assign_to from oa_bug WHERE STATUS = '1') order by a.name asc
",
'open_window_style'=>"popup:middle",
'pagerows'=>"20",
'actions'=>"add,search,search2,filter,export,page",
'cols'=>"2",
'fields'=>array(
 array('id'=>"username",'name'=>"账号",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"name",'name'=>"姓名",'fieldtype'=>"string",'style'=>"text-align:center",),
 array('id'=>"todayaddbug",'name'=>"今日创建bug",'fieldtype'=>"int",'style'=>"text-align:center",),
 array('id'=>"todaybug",'name'=>"今日新增bug数",'fieldtype'=>"int",'style'=>"text-align:center",),
 array('id'=>"yesterdaybug",'name'=>"昨日完成量",'fieldtype'=>"int",'style'=>"text-align:center",),
 array('id'=>"today_completebug",'name'=>"今日bug完成数",'fieldtype'=>"int",'style'=>"text-align:center",),
 array('id'=>"shengyu_num",'name'=>"剩余量",'fieldtype'=>"int",'style'=>"text-align:center",),
),

) ?>