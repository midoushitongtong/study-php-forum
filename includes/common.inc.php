<?php
//防止includes被恶意调用
if(!defined('IN_TG')){
	exit('Access Defined!');
}
//字符集编码
header('Content-Type:text/html;charset=utf-8');
//引入核心函数库
require './includes/global.func.php';
//引入hanshu
require './includes/mysql.func.php';
//执行耗时
define('START_TIME',returntime());
//自动转义字符的
define('GPC',get_magic_quotes_gpc());
//连接数据库
define('DB_HOST','localhost');
define('DB_USER','yyc');
define('DB_PWD','593690203');
define('DB_NAME','testguest');
//连接数据库
_connect();//连接MYSQL数据库
_select_db();//选着指定数据库
_set_names();//设置字符集

// AND
// tg_touser='{$_COOKIE['username']}'
//短信提醒                                                                    //COUNT 获取字段综合
$_message = @_fetch_array("SELECT 
																COUNT(tg_id) 
														AS 
																count 
												   	  FROM 
																tg_message 
												     WHERE 
												 				tg_state=0
												 	   AND
												 	   			tg_touser='{$_COOKIE['username']}'
");
if (empty($_message['count'])) {
	$GLOBALS['message'] = '<strong class="noread"><a href="member_message.php">(0)</a></strong>';
} else {
	$GLOBALS['message'] = '<strong class="read"><a href="member_message.php">('.$_message['count'].')</a></strong>';
}






















//网站系统初始

if(!!$_rows = _fetch_array("SELECT
                                   tg_webname,tg_article,tg_blog,tg_photo,tg_skin,tg_post,tg_re,tg_code,tg_register,tg_string
                              FROM
                                   tg_system
                             WHERE
                                   tg_id=1
                             LIMIT
                                   1"

)){
  $_system = array();
  $_system['webname'] = $_rows['tg_webname'];
  $_system['article'] = $_rows['tg_article'];
  $_system['blog'] = $_rows['tg_blog'];
  $_system['photo'] = $_rows['tg_photo'];
  $_system['skin'] = $_rows['tg_skin'];
  $_system['post'] = $_rows['tg_post'];
  $_system['re'] = $_rows['tg_re'];
  $_system['code'] = $_rows['tg_code'];
  $_system['register'] = $_rows['tg_register'];
  $_system['string'] = $_rows['tg_string'];
  $_system = _html($_system);
  
  if (@$_COOKIE['skin']) {
  	  $_system['skin'] = $_COOKIE['skin'];
  }
}else{
  exit('系统表异常啊');
}





















































?>