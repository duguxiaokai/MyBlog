<?php return array(
'id'=>"oa_project_sharetome",
'title'=>"我的项目",
'base'=>"oa_project_my",
'openurl'=>"Project/projectdetail/showcopy/true",
'filter'=>"id in (select share_object_id from sys_share where loginuser()<>'' and shareto like '%loginuser_noyinhao()%')",
'more'=>"[PUBLIC]",

) ?>