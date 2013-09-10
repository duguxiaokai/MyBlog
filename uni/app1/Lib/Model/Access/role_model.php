<?php
/*
说明：
'public'=>'td_member,oa_task',//表示角色public拥有所有名称为“td_member”和“oa_task”的模型的权限
'manager'=>'td_buy*,//表示角色manager拥有所有名称以“td_buy”为前缀的模型的权限
'admin'=>'*,//表示角色admin拥有所有所有模型的权限
'*'=>'sys_help',//表示模型sys_help完全公开，无权限限制
*/

return array(
'admin'=>'*',//所有模型
'manager'=>'*',//后台管理员  所有模型


'zongjingli'=>'*',//总经理模型

"landz_service"=>"*",//运营总监
"landz_zjzl"=>"*",//总监助理

"developer"=>"*",//开发者


//所有完全公开的模型放在这里
"*"=>"
//系统
sys_statdata_my,sys_login_pwd,sys_log_my,sys_statdata_myhistory,sys_login_pwd_noemail,sys_uploadexcel,
//丽兹行
,landz_mail_tpl,landz_mail_tpl,landz_customer,landz_xqinfo_base,landz_xqinfo_base2,landz_xqinfo_fuwu,landz_xqinfo_fuwu2,landz_fyinfo_advanced2,landz_fyinfo_advanced,landz_fyinfo_base,landz_fyinfo_business2,landz_fyinfo_base_bycode,landz_fyinfo_business,landz_genjin_fyinfo,landz_genjin_fyinfo_crm,
landz_customer_detail,landz_fcinfo,landz_xqinfo,landz_jiaoyi,landz_jiaoyi_zl,landz_genjin_customer,landz_genjin_customer_crm,landz_customer_family,landz_fyinfo,landz_fyinfo,landz_sms,landz_mail,
//建研
crm_tuisuo,ruku,crm_customer_order,crm_customer,

//oa公开模型
oa_task_day,oa_info_lasted,oa_info_lasted2,oa_bug,oa_bug_tongji,oa_bug_genjin,oa_mycreate_bug,oa_bug_all,oa_bug_reply,oa_task_add_day,oa_task_week_detail,oa_note_my,oa_attendance_mng,oa_attendance,oa_absence,oa_project_my,oa_tasktype,oa_info,oa_file,oa_project_report,oa_project_report_nextweek,oa_project_report_problem,oa_project_report_result,sys_staff_mcss,sys_staff_base,sys_user_mcss,sys_position_mcss,oa_task_add_day1,sys_formfile,oa_project_basic,oa_project,oa_project_income,biz_order_product_yagaoincome,oa_project_invoice,oa_project_cost,oa_project_add,oa_project_task,oa_task_xd,oa_task_myexecute,oa_task_assign_my,oa_task_day_list,oa_task_week_list,oa_project_my_list,biz_opportunity_my_list,
//crm
biz_opportunity_my2,biz_opportunity_product,biz_product_list,biz_fundrestream_list,
oa_task_myexecute,oa_project_newtask,

//meimeng
meimeng_student_talk,meimeng_class_notice,meimeng_student_weekly_list,meimeng_customermessage,meimeng_class_notice,meimeng_star,meimeng_classteach,meimeng_student,meimeng_class_story,meimeng_class_thanks,meimeng_student_picture,meimeng_student_talk,meimeng_student_weekly,meimeng_note_my,oa_note,meimeng_user,meimeng_class_plan_my,meimeng_customermessage,

//苗健
mj_admin_agent_url_edit,mj_admin_agent_url,mj_news,mj_admin_news_notes,mj_medialibary_list,mj_customer_setting,

//服装服饰
fco_accreditation_person,fco_user_register,fco_sys_user,fco_accreditation_agency,fco_opportunity,fco_works,fco_worksgroup,
fco_works_discuss,fco_user_mycards,fco_sysnotice,fco_discuss_list,fco_favorite_list,fco_message_list,fco_sysnotice_list,fco_user_info,
fco_user_photo,fco_user,fco_accreditation,fco_accreditation_agencyaccess,fco_accreditation_personaccess,fco_circle,fco_circle_discuss,
fco_circle_speak,fco_discussion,fco_favorite,fco_message,fco_opportunity_discuss,fco_seluser_list,fco_sysnotice_user,

//兼职招聘
recruitment_sys_user,recruitment_user,
",
)
?>