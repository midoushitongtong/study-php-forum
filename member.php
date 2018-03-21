<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','member');
require './includes/common.inc.php';

//是否正常登陆,才允许进入本页面
if(isset($_COOKIE['username'])){
  //获取数据
  $_rows = _fetch_array("SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_level,tg_reg_time FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1");
  //判断数据是否存在
  if($_rows){
  	$_html =array();
  	$_html['username'] = _html($_rows['tg_username']);
  	$_html['sex'] = _html($_rows['tg_sex']);
  	$_html['face'] = _html($_rows['tg_face']);
  	$_html['email'] = _html($_rows['tg_email']);
  	$_html['url'] = _html($_rows['tg_url']);
  	$_html['qq'] = _html($_rows['tg_qq']);
  	$_html['reg_time'] = _html($_rows['tg_reg_time']);
  	switch($_rows['tg_level']){
  		case 0:
  		  $_html['level'] = '普通会员';
  		  break;
  		case 1:
  		  $_html['level'] = '管理员';
  		  break;
  		  //没有的话 default
  		default:
  		  $_html['level'] = '出错了';
  	}
  }else{
  	_alert_back('此用户不存在');
  }
}else{
  //没登录访问 跳转登陆
  _location(null,'login.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
</head>
<body>
<?php 
require './includes/header.inc.php';
?>

<div id="member">
  <?php 
  require './includes/member.inc.php';
  ?>
  <div id="member_main">
    <h2>会员管理中心</h2>
    <dl>
      <dd>用 户 名 ：<?php echo $_html['username']?></dd>
      <dd>性 　  别 ：<?php echo $_html['sex']?></dd>
      <dd>头 　  像 ：<?php echo $_html['face']?></dd>
      <dd>电子邮件：<?php echo $_html['email']?></dd>
      <dd>主　　页：<?php echo $_html['url']?></dd>
      <dd>Q 　　Q：<?php echo $_html['qq']?></dd>
      <dd>注册时间：<?php echo $_html['reg_time']?></dd>
      <dd>身 　  份 ： <?php echo $_html['level']?></dd>
    </dl>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>