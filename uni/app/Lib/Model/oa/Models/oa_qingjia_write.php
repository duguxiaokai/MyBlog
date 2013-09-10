<?php return array(
'id'=>"oa_qingjia_write",
'title'=>"填写请假单",
'base'=>"oa_qingjia",
'wayofcopyfields'=>"parent_and_child",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"staffid",'name'=>"请假人",'readonly'=>"true",),
 array('id'=>"type",'name'=>"请假类别",),
 array('id'=>"begindate",'name'=>"开始日期",),
 array('id'=>"enddate",'name'=>"结束日期",),
 array('id'=>"span",'name'=>"请假小时数",),
 array('id'=>"reason",'name'=>"请假原因",),
 array('id'=>"approver",'name'=>"审批人",),
 array('id'=>"status",'name'=>"状态",'readonly'=>"true",),
 array('id'=>"notes",'name'=>"备注",'readonly'=>"true",),
 array('id'=>"SYS_ADDUSER",'name'=>"创建者",),
 array('id'=>"SYS_ADDTIME",'name'=>"创建时间",),
 array('id'=>"SYS_EDITUSER",'name'=>"更新人",),
 array('id'=>"SYS_EDITTIME",'name'=>"更新时间",),
 array('id'=>"SYS_ORGID",'name'=>"组织ID",),
),

) ?>