<?php return array(
'id'=>"oa_pm_inputprojectcost_stat",
'title'=>"工时填报统计",
'sql'=>"select b.name as staffname,c.name as period,count(*) as num
from oa_pm_inputprojectcost a
left join sys_staff b on b.id=a.staffid
left join oa_pm_period c on c.id=a.period
group by b.name,c.name",
'orderby'=>"period desc",
'groupby'=>"y",
'pagerows'=>"20",
'actions'=>"search,page,groupby",
'fields'=>array(
 array('id'=>"staffname",'name'=>"填报人",),
 array('id'=>"num",'name'=>"填报数目",),
 array('id'=>"period",'name'=>"周次",),
),

) ?>