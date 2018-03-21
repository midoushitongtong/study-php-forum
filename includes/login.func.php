<?php
//防止includes被恶意调用
if(!defined('IN_TG')){
  exit ('Access Defined!');
}
//检查验证码错误弹窗函数_alert_back是否存在
if(!function_exists('_alert_back')){
  exit('_alert_back函数不存在,请检查!');
}
if(!function_exists('_mysql_string')){
  exit('_mysql_string函数不存在,请检查!');
}
//检查验证码错误弹窗函数_alert_back是否存在

//cookie
//执行cookie 保存用户信息
function _setcookies($_username,$_uniqid,$_time){
	setcookie('username',$_username);
	setcookie('uniqid',$_uniqid);
	switch($_time){
	    //登录默认不保存
		case '0';
		  setcookie('username',$_username);
		  setcookie('uniqid',$_uniqid);
		  break;
		//保存一天
		case '1';
		  setcookie('username',$_username,time()+86400);
		  setcookie('uniqid',$_uniqid,time()+86400);
		  break;
		//保存一个星期
        case '2';
		  setcookie('username',$_username,time()+604800);
		  setcookie('uniqid',$_uniqid,time()+604800);
		  break;
		//保存一个月
        case '3';
		  setcookie('username',$_username,time()+2592000);
		  setcookie('uniqid',$_uniqid,time()+2592000);
		  break;
	}
}
//cookie

  //验证用户名
  //string $_string 最大位数  $_min_num 最小位数 $_mix_num $_string 用户名 return 过滤后的用户名
  function _check_username($_string,$_min_num,$_max_num){
    //验证去除两边空格
    $_string = trim($_string);
    //判断长度小于两位或者大于20位都不行
    //mg_strlen  解决中文字符串 字符数 用utf-8 统一
    if(mb_strlen($_string,'utf-8') < $_min_num || mb_strlen($_string,'utf-8') > $_max_num){
      _alert_back('用户名长度不得小于'.$_min_num.'或大于'.$_max_num.'位');
    }
    //限制敏感字符
    $_char_pattern = '/[<>\'\"\ ]/';
    if (preg_match($_char_pattern,$_string)) {
      _alert_back('用户名不得包含敏感字符');
    }
   return _mysql_string($_string);
}
  //验证用户名
  
  //验证密码
function _check_password($_string,$_min_num){
  //判断密码
  if(strlen($_string)<$_min_num){
    _alert_back('密码不能小于'.$_min_num.'位');
  }
  //对密码进行加密并返回
  return sha1($_string);
}
  //验证密码
  
  //保留时间
function _check_time($_string){
    $time = array('0','1','2','3');
    if(!in_array($_string,$time)){
    	_alert_back('保留方式出错');
    }
	return _mysql_string($_string);
}
  //保留时间
  












