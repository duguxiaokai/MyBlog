<?php return array(
'id'=>"oa_task_week_list",
'title'=>"周报列表",
'tablename'=>"oa_task",
'sql'=>"select a.id,a.cat,a.begindate,a.enddate,a.name,a.finishper,a.executerid,a.notes,a.tag,a.worktime,a.projectid,a.SYS_ORGID,a.SYS_ADDUSER,a.SYS_ADDTIME,a.SYS_EDITUSER,a.SYS_EDITTIME,(select name from oa_project where id=a.projectid)  as projectname from oa_task a where SYS_ORGID=orgid() and finishper=0
",
'pagerows'=>"5",
'wayofcopyfields'=>"onlycopyprop",
'cols'=>"1",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"cat",'name'=>"工作类别",'length'=>"50",'defaultdata'=>"周报",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"begindate",'name'=>"开始日期",'type'=>"date",'isvisible'=>"false",'visibleWhenAdd'=>"false",'nullable'=>"false",),
 array('id'=>"enddate",'name'=>"结束日期",'type'=>"date",'isvisible'=>"false",'visibleWhenAdd'=>"false",'nullable'=>"false",),
 array('id'=>"name",'name'=>"主题",'type'=>"textarea",'forsearch'=>"true",'nullable'=>"false",'style'=>"width:200px",),
 array('id'=>"finishper",'name'=>"完成状态",'fieldtype'=>"string",'type'=>"radio",'data'=>"0:未完成,1:已完成,2:已取消",'defaultdata'=>"0",'style'=>"width:20px",),
 array('id'=>"executerid",'name'=>"执行人",'type'=>"dropdown",'data'=>"sql:select id,name from sys_staff where SYS_ORGID=orgid()",'defaultdata'=>"Me()",'isvisible'=>"false",'visibleWhenAdd'=>"false",'style'=>"width:50px",),
 array('id'=>"notes",'name'=>"主管评价",'fieldtype'=>"string",'type'=>"textarea",'isvisible'=>"false",'visibleWhenAdd'=>"false",'style'=>"text-align:center;width:20px",),
 array('id'=>"tag",'name'=>"标签",'type'=>"dropdown",'data'=>"1:计划概述,2:工作条目,3:总结",'defaultdata'=>"2",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",'type'=>"SYS_ADDUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",'fieldtype'=>"datetime",'type'=>"SYS_ADDTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",'forsearch'=>"true",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",'fieldtype'=>"string",'type'=>"SYS_EDITUSER",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"worktime",'name'=>" 工时",'fieldtype'=>"int",'type'=>"text",'style'=>"text-align:center;width:20px",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",'fieldtype'=>"datetime",'type'=>"SYS_EDITTIME",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
 array('id'=>"SYS_ORGID",'name'=>"组织id",'defaultdata'=>"orgid()",'isvisible'=>"false",'visibleWhenAdd'=>"false",),
 array('id'=>"projectid",'name'=>"项目",'type'=>"dropdown",'data'=>"sql:select id,name from oa_project",'isvisible'=>"false",'visibleWhenAdd'=>"false",'readonly'=>"true",),
),

) ?>