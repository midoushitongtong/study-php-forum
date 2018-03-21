<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','manage');
require './includes/common.inc.php';
_manage_login();
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
  require './includes/manage.inc.php';
  ?>
  <div id="member_main">
    <h2>会员管理中心</h2>
    <dl>
      <dd>服务器主机名称 : <?php echo $_SERVER['SERVER_NAME']?></dd>
      <dd>通信协议名称 : <?php echo $_SERVER['SERVER_PROTOCOL']?></dd>
      <dd>服务器IP : <?php echo $_SERVER['SERVER_PORT']?></dd>
      <dd>客户端IP : <?php echo $_SERVER['SERVER_ADDR']?></dd>
      <dd>服务器连接 : <?php echo $_SERVER['REMOTE_PORT']?></dd>
      <dd>客户端连接 : <?php echo $_SERVER['REMOTE_ADDR']?></dd>
      <dd>管理员邮箱 : <?php echo $_SERVER['SERVER_ADMIN']?></dd>
      <dd>HOST 顶部内容 : <?php echo $_SERVER['HTTP_HOST']?></dd>
      <dd>服务器主目录 : <?php echo $_SERVER['DOCUMENT_ROOT']?></dd>
      <dd>服务器系统盘 : <?php echo $_SERVER['SystemRoot']?></dd>
      <dd>脚本执行路径 : <?php echo $_SERVER['SCRIPT_FILENAME']?></dd>
      <dd>Apache和PHP版本 : <?php echo $_SERVER['SERVER_SOFTWARE']?></dd>
    </dl>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>