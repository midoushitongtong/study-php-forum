<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','q');
require './includes/common.inc.php';
//引入css公共文件
require './includes/title.inc.php';
//初始化
if(isset($_GET['num']) && isset($_GET['path'])){
  if(is_dir(__FILE__.$_GET['path'])){
  	_alert_back('非法操作');
  }
}else{
  _alert_back('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>图选择</title>
<script type="text/javascript" src="js/qopener.js" >
</script>
</head>
<body>
<div id="q">
  <h3>选择头像</h3>
  <dl>
    <?php foreach (range(1,$_GET['num']) as $_num) {?>
      <dd><img src="<?php echo $_GET['path'].$_num ?>.gif" alt="<?php echo $_GET['path'].$_num?>.gif" title="头像<?php echo $_num?>" /></dd>
    <?php }?>
    
  </dl>
</div>

</body>
</html>












