<?php return array(
'id'=>"123456",
'title'=>"6666",
'sql'=>"select * from (select a.*, 
(select count(*) from (select title from mj_searched as s left join mj_routine_monitor as b 
on s.news_id=b.project where s.mainkeynum>=(select keyFre from mj_routine_monitor c where c.id=s.mainkey ) 
group by s.url) s where s.title=a.title ) as frequency from ( 

select * from (select d.* ,'caozuo'as caozuo , 
GROUP_CONCAT(b.keywords separator ',') as keywords, 
GROUP_CONCAT(b.similarkeywords separator ',')as skey 
from mj_searched as d left join mj_routine_monitor as b 
on d.news_id=b.project where (d.similarkeyNum+d.mainkeynum)>=(select keyFre from mj_routine_monitor c where c.id=d.mainkey ) 
group by d.url) d group by d.title 

) a 
) a where ((type <> '微博' ) and (division='监测' and custid=1 and news_id=87 and delrecord <>1 and (similarnews='0' or similarnews='') and publishedtime <= '2013-06-03 23:59:59')) order by publishedtime desc limit 0,10 ",
'more'=>"[PUBLIC]",
'fields'=>array(
 array('id'=>"id",'name'=>"id",),
 array('id'=>"title",'name'=>"title",),
),

) ?>