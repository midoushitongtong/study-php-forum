<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','manage_job');
require './includes/common.inc.php';
_manage_login();

//提交

if(@$_GET['action'] == 'add'){
      if(!!$_rows = _fetch_array("SELECT 
                                        tg_uniqid
                                    FROM
                                        tg_user
                                   WHERE
                                        tg_username='{$_COOKIE['username']}'
                                   LIMIT
                                        1"
    )){
            _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
        	$_clean = array();
        	$_clean['username'] = $_POST['manage'];
        	$_clean = _mysql_string($_clean);
        	//修改等级啊啊
        	_query("UPDATE tg_user SET tg_level=1 WHERE tg_username='{$_clean['username']}'");
            if(_affected_rows() == 1){
            	_close();
            	_location('OK',SCRIPT.'.php.');
            }else{
            	_close();
            	_alert_back('不能添加啊啊');
            }
      } else {
      	_alert_back('你不能这么做');
      }
}

if(@$_GET['action'] == 'job' && isset($_GET['id'])){
	if(!!$_rows = _fetch_array("SELECT
	                                  tg_uniqid
	                              FROM
	                                  tg_user
	                             WHERE
	                                  tg_username='{$_COOKIE['username']}'
	                             LIMIT
	                                  1"
      )){
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//执行
		_query("UPDATE tg_user SET tg_level=0 WHERE tg_username='{$_COOKIE['username']}' AND tg_id='{$_GET['id']}'");
		if(_affected_rows() == 1){
			_close();
			_session_destroy();
			_location('可以','index.php');
			
		}else{
			_close();
			_location('不行','manage_job.php');
		}
	}else{
		_alert_back('不能这么做啊啊');
	}
}

global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_user",13);
$_result = _query("SELECT 
                          tg_username,tg_reg_time,tg_email,tg_id,tg_level
                     FROM
                          tg_user
                    WHERE
                          tg_level=1
                  ORDER BY
                          tg_reg_time
                    LIMIT
                          $_pagenum,$_pagesize
    "); 
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
            if($_COOKIE['username'] == $_html['username']){
            	$_job_html = '<a href="manage_job.php?action=job&id='.$_html['id'].'">辞职</a>';
            }else{
            	$_job_html = '不能做';
            }
      ?>
      <tr><td><?php echo $_html['id']?></td><td><?php echo $_html['username']?></td><td><?php echo $_html['email']?></td><td><?php echo $_html['reg_time']?></td><td><?php echo $_job_html?></td></tr>    
      <?php 
           }
      ?>
    </table>
    <form method="post" action="?action=add">
      <input type="text" name="manage" class="text" /><input type="submit" value="加入" />
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