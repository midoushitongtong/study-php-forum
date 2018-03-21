<?php
//开始session提交给自己
session_start();
//授权
define('IN_TG',true);
//样式表名字
define('SCRIPT','login');
//引入核心函数库
require './includes/common.inc.php';
//登录状态无法执行操作
_login_state();
global $system;
//开始处理登陆状态
if(@$_GET['action'] == 'login'){
  if(!empty($_system['code'])){
    _check_code($_POST['code'],$_SESSION['code']);
  }
  //引入验证文件
  require './includes/login.func.php';
  //接受数据
  $_clean = array();
  $_clean['username'] = _check_username($_POST['username'],2,20);
  $_clean['password'] = _check_password($_POST['password'],6);
  $_clean['time'] = _check_time($_POST['time']);
  //到数据库去验证 3部分 账户是否激活 用户名密码是否正确
  //!!为1 !为0 
  if(!!$_rows = _fetch_array("SELECT tg_username,tg_uniqid,tg_level FROM tg_user WHERE tg_username='{$_clean['username']}' AND tg_password='{$_clean['password']}' AND tg_active='' LIMIT 1")){
     //登陆成功后记录登录信息
     _query("UPDATE tg_user SET 
                               tg_last_time = NOW(),
                               tg_last_ip='{$_SERVER['REMOTE_ADDR']}',
                               tg_login_count=tg_login_count+1
                          WHERE
                               tg_username='{$_rows['tg_username']}'                              
                               ");
     //_close();
     //_session_destroy();
     _setcookies($_rows['tg_username'],$_rows['tg_uniqid'],$_clean['time']);
     //后台
     if($_rows['tg_level'] == 1){
     	$_SESSION['admin'] = $_rows['tg_username'];
     }
  	 _location(null,'member.php');
  	 _close();
  }else{
     _close();
     //_session_destroy();
     _location('用户名密码不正确或者改账户未被激活!','login.php');
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</head>

<body>
<?php
require './includes/header.inc.php';
?>

<div id="login">
  <h2>登陆</h2>
  <form method="post" name="login" action="login.php?action=login">
    <dl>
      <dt>123</dt>
      <dd>用 户 名 ：<input type="text" name="username" class="text" /></dd>
      <dd>密 　 码 ：<input type="password" name="password" class="text" /></dd>
      <dd>保　　留 ：<input type="radio" name="time" value="0" checked="checked" /> 不保留：<input type="radio" name="time" value="1" /> 一天 <input type="radio" name="time" value="2" /> 一周 <input type="radio" name="time" value="3" /> 1个月 </dd>
      <?php if(!empty($_system['code'])){?>
        <dd>验 证 码 ：<input type="code" name="code" class="text code" /><img src="code.php" id="code"/></dd>
      <?php }?>
      <dd><input type="submit" value="登陆" class="button" /><input type="button" value="注册" id="location" class="button location" /></dd>
    </dl>
  </form>
</div>

<?php
require './includes/footer.inc.php';
?>
</body>
</html>