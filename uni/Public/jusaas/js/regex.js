//正则表达式

var regex=new Object();

/*
下面是正则表达式函数
*/
//邮件校验
function IsEmail(str){
var parttern=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
return parttern.test(str);
}
//手机校验
function IsPhone(str){
var parttern=/^[1][0-9]{10}$/;
return parttern.test(str);
}
//电话校验
function IsTel(str){
var parttern=/^(\s*\d+-?\d+[-,\s]?\s?\d+\s*)*$/;
return parttern.test(str);
}
//身份证校验
function IsIdentity(str){
var parttern=/(^\d{15}$)|(^\d{17}(\d|X)$)/;
return parttern.test(str);
}
//邮编校验.6位数字
function IsPost(str){
var parttern=/^\d{6}$/;
return parttern.test(str);
}
//整数校验
function IsInt(str){
var parttern=/^\d+$/;
return parttern.test(str);
}
//貌似是正整数,--by桔子
function IsPosInt(str){
var parttern=/^[0-9]*[1-9][0-9]*$/;
return parttern.test(str);
}
//浮点型校验
function IsFloat(str){
var parttern=/^[-\+]?\d+(\.\d+)?$/;
return parttern.test(str);
}
//时间类型
function IsDatetime(str){
var parttern=/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
return parttern.test(str);
}
//日期类型
function IsDate(str){
var parttern=/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
return parttern.test(str);
}
