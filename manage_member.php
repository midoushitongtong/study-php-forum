<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','manage_member');
require './includes/common.inc.php';
_manage_login();
global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_user",13);
$_result = _query("SELECT 
                          tg_username,tg_reg_time,tg_email,tg_id
                     FROM
                          tg_user
                  ORDER BY
                          tg_reg_time
                    LIMIT
                          $_pagenum,$_pagesize
    ");




//


if(@$_GET['action'] == 'del' && isset($_GET['id'])){
	if(!!$_rows = _fetch_array("SELECT
	                                   tg_id
                                  FROM
	                                   tg_user
	                             WHERE
	                                   tg_id='{$_GET['id']}'
	                             LIMIT
	                                   1 
	    ")){
	    
	    if($_rows2 = _fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
	    _uniqid($_rows2['tg_uniqid'],$_COOKIE['uniqid']);
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	       _query("DELETE FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1");
	       if(_affected_rows() == 1){
	       	_close();
	       	_location('好了','manage_member.php');
	       } else {
	       	_close();
	       	_location('好了','http://www.jindong.com');
	       }
	    }else{
	    	_alert_back('请登录');
	    }
	}else{
		_alert_back('此用户不存在');
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
<script type="text/javascript" src="js/member_message.js"></script>
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
    <form method="post" action="?action=delete">
    <table border="0">
      <tr><th>ID</th><th>会员名</th><th>邮件</th><th>注册时间</th><th>操作</th></tr>
      <?php 
            $_html = array();
            while(!!$_rows = _fetch_array_list($_result)){
            $_html['id'] = $_rows['tg_id'];
            $_html['username'] = $_rows['tg_username'];
            $_html['reg_time'] = $_rows['tg_reg_time'];
            $_html['email'] = $_rows['tg_email'];
            $_html = _html($_html);
      ?>
      <tr><td><?php echo $_html['id']?></td><td><?php echo $_html['username']?></td><td><?php echo $_html['email']?></td><td><?php echo $_html['reg_time']?></td><td>[<a href="?action=del&id=<?php echo $_html['id']?>">删</a>] [修]</td></tr>    
      <?php 
           }
      ?>
    </table>
    </form>
    <?php 
      _free_result($_result);
      _paging(1);
    ?>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>