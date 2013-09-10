<?php return array(
'id'=>"list_task1",
'title'=>"任务列表",
'tablename'=>"oa_task",
'openurl'=>"default",
'editurl'=>"default",
'addurl'=>"default",
'pagerows'=>"10",
'actions'=>"all",
'fields'=>array(
 array('id'=>"id",'name'=>"ID",'visibleWhenAdd'=>"true",'readonly'=>"true",),
 array('id'=>"project",'name'=>"所属项目",'type'=>"dropdown",'data'=>",儿童瑜伽,td:铜道,td:建研,商城,MCSS,其它",'forsearch'=>"true",),
 array('id'=>"name",'name'=>"工作名称",'type'=>"file",'forsearch'=>"true",),
)
) ?>