<?php return array(
'id'=>"whole_tongji",
'title'=>"统计列表",
'tablename'=>"crm_historydata",
'openurl'=>"CRM/Tongji/openwhole_tongji",
'addurl'=>"CRM/Tongji/newwholetongji",
'open_window_style'=>"popup:small",
'pagerows'=>"10",
'actions'=>"add,del,open,search,search2,page",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",),
 array('id'=>"category",'name'=>"统计类别",'forsearch'=>"true",),
 array('id'=>"year",'name'=>"时间段",'forsearch'=>"true",),
 array('id'=>"addtime",'name'=>"统计时间点",'forsearch'=>"true",),
 array('id'=>"owner",'name'=>"统计者",'forsearch'=>"true",),
)
) ?>