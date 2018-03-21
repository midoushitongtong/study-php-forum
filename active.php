<?php
session_start();
define('IN_TG',true);
define('SCRIPT','active');
require './includes/common.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
//激活处理
if(!isset($_GET['active'])){
  _alert_back('非法操作');
}
if(isset($_GET['action']) && isset($_GET['active']) && $_GET['action'] == 'ok'){
    $_active = _mysql_string($_GET['active']);
    if(_fetch_array("SELECT tg_active FROM tg_user WHERE tg_active='$_active' LIMIT 1")){
    	//将tg_active设置零
    	_query("UPDATE tg_user SET tg_active='' WHERE tg_active='$_active' LIMIT 1");
        if(_affected_rows() == 1){
        	_close();
        	_location('账户激活成功','login.php');
        }else{
        	_close();
            _location('账户激活失败','register.php');
        }
    }else{
    	'非法操作';
    }
}
//激活处理
?>
<script type="text/javascript" src="js/register.js"></script>
</head>

<body>
<?php
require './includes/header.inc.php';
?>

<div id="active">
  <h2>激活账户</h2>
  <p>模拟邮件激活功能,点击邮件激活账户</p>
  <p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']?>"><?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]?>active.php?action=ok&amp;active=<?php echo $_GET['active'] ?></a></p>
</div>

<?php
require './includes/footer.inc.php';
?>
</body>
</html>