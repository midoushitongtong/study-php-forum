<?php
define('IN_TG',true);
define('SCRIPT','face');
require './includes/common.inc.php';
//引入css公共文件
require './includes/title.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/opener.js" >
</script>
</head>
<body>
<div id="face">
  <h3>选择头像</h3>
  <dl>
	<?php foreach (range(1,9) as $num) {?>
  	<dd><img src="face/m0<?php echo $num?>.gif" alt="face/m0<?php echo $num?>.gif" title="头像<?php echo $num?>" /></dd>
    <?php }?>
    </dl>
    <dl>
    <?php foreach (range(10,64) as $num) {?>
    <dd><img src="face/m<?php echo $num?>.gif" alt="face/m<?php echo $num?>.gif" title="头像<?php echo $num?>" /></dd>
    <?php }?> 
    </dl>

</div>

</body>
</html>












