<?php
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

//判断唯一标示符
function _check_uniqid($_first_uniqid,$_end_uniqid){
  if((strlen($_first_uniqid) != 40) || ($_first_uniqid != $_end_uniqid)){
  	_alert_back('唯一标识符异常');
  }
  
  return _mysql_string($_first_uniqid); 
}
//判断唯一标示符

//验证用户名
  //string $_string 最大位数  $_min_num 最小位数 $_mix_num $_string 用户名 return 过滤后的用户名
function _check_username($_string,$_min_num,$_max_num){
    global $_system;
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//敏感用户名
	$mg = explode('|',$_system['string']);
	//$mg ='';
	//$mg[1]='admin';
	//$mg[2]='admin';
	//$mg[3]='administrator';
	//告诉用户那些用户名不能注册
	foreach ($mg as $value){
     $mg_string = '';
	   $mg_string.=$value;
	}
	//绝对匹配
	if(in_array($_string,$mg)){
	  _alert_back($mg_string.'敏感用户名不得注册');
	}
	//返回值
	//addslashes转义字符串并返回
	return _mysql_string($_string);
}
//验证用户名

//验证密码
function _check_password($_first_pass,$_end_pass,$_min_num){
  //判断密码
  if(strlen($_first_pass)<$_min_num){
    _alert_back('密码不能小于'.$_min_num.'位');
  }
  
  //密码和密码确认必须一致
  if($_first_pass != $_end_pass){
    _alert_back('密码和确认密码不一致');
  }
  //对密码进行加密并返回
  return _mysql_string(sha1($_first_pass));
}
//验证密码

//验证修改时的密码
function _check_modify_password($_string,$_min_num){
	//判断密码
	if(!empty($_string)){
    	if(strlen($_string) < $_min_num){
    		_alert_back('密码不得小于'.$_min_num.'位');
    	}
	}else{
		return null;
	}
	//加密
	return sha1($_string);
}
//验证修改时的密码

//验证密码提示
function _check_question($_string,$_min_num,$_max_num){
    $_string = trim($_string);
    //密码提示不能小于$_min_num位不得大于$_max_num位
	if(mb_strlen($_string,'utf-8')<$_min_num || mb_strlen($_string,'utf-8')>$_max_num){
	   _alert_back('密码提示不得小于'.$_min_num.'或者大于'.$_max_num.'位');
	}
	//返回密码提示
	return _mysql_string($_string);
}
//验证密码提示

//验证密码回答
function _check_answer($_ques,$_answer,$_min_num,$_max_num){
    $_answer = trim($_answer);
    //密码提示不能小于$_min_num位不得大于$_max_num位
	if(mb_strlen($_answer,'utf-8')<$_min_num || mb_strlen($_answer,'utf-8')>$_max_num){
	   _alert_back('密码回答不得小于'.$_min_num.'或者大于'.$_max_num.'位');	
	}
	//密码提示与回答不能一致
	if($_ques==$_answer){
		_alert_back('密码提示与回答不能相同');
	}
    //加密返回
    return _mysql_string(sha1($_answer));
}
//验证密码回答

//返回字符串并转义  性别
function _check_sex($string){
	return _mysql_string($string);
}
//返回字符串并转义 

//返回字符串并转义  头像
function _check_face($_string){
	return _mysql_string($_string);
}
//返回字符串并转义  头像

//过滤邮件
function _check_email($_string,$_min_num,$_max_num){
  
  //empty 邮件是选填  如果为空不处理 如果填了才处理下面的函数
    //参考xxx.@163.com
    //[a-zA-Z0-9] \w
    //[\w\-\.]16.3
    //[\w\+].com.com.cn
       if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)$/',$_string)){
        _alert_back('邮件格式不正确!');
    }
       if(strlen($_string)<$_min_num || strlen($_string)>$_max_num){
        _alert_back('邮件长度不合法');
    }

  //返回结果
  return _mysql_string($_string);
}
//过滤邮件

//过滤QQ
function _check_qq($_string){
  //QQ 是选填  如果为空不处理如果填了才处理下面的函数
  if(empty($_string)){
  	return null;
  }else{
      //123456
  	if(!preg_match('/^[1-9]{1}[0-9]{6,10}$/',$_string)){
  	 _alert_back('QQ号码不正确');
  	}
  }
  return _mysql_string($_string);
}
//过滤QQ

//过滤个人网页地址
function _check_url($_string,$_max_num){
  //网址 是选填  如果为空或默认http://不处理 如果填了才处理下面的函数
  if(empty($_string) || ($_string == 'http://')){
  	return null;
  }else{
    //http://baidu.com
    if(!preg_match('/^https?:\/\/(\w+\.)?[\w.\-\.]+(\.\w+)+$/',$_string)){
    	_alert_back('网址不正确');
    }
    if(strlen($_string)>$_max_num){
    	_alert_back('网址太长');
    }
  }
  return _mysql_string($_string);
}
//过滤个人网页地址

//短信
function _check_content($_string){
	if(mb_strlen($_string) < 3 || mb_strlen($_string) > 200){
		_alert_back('短息内容内容不的小于3位或大于200位');
	}
	return _mysql_string($_string);
}
//短信



//post
function _check_post_title($_string,$_min,$_max){
	if(mb_strlen($_string,'utf-8') < $_min || mb_strlen($_string,'utf-8') > $_max){
		_alert_back('标题不得小于'.$_min.'位,大于'.$_max.'位');
	}
  return $_string;
}
function _check_post_content($_string,$_num){
  if(mb_strlen($_string,'utf-8') < $_num){
    _alert_back('帖子内容不得小于'.$_num.'位');
  }
  return $_string;
}
function _check_autograph($_string,$_num){
  if(mb_strlen($_string,'utf-8') > $_num){
  	_alert_back('内容不得小于'.$_num.'位');
  }
  return $_string;
}










//photo


function _check_dir_name($_string,$_min,$_max){
	if (mb_strlen($_string,'utf-8') < $_min || mb_strlen($_string,'utf-8') > $_max ) {
		_alert_back('名称不能小于'.$_min.'位或大于'.$_max.'位');
	}
	return $_string;
}

function _check_dir_password($_string,$_num){
		if(strlen($_string) < $_num){
			_alert_back('密码不能小于'.$_num.'位啊啊啊啊啊');
		} 
	return sha1($_string) ;
}

function _check_photo_url($_string) {
	if (empty($_string)) {
		_alert_back('地址不能为空');
	}
	return $_string;
}

?>