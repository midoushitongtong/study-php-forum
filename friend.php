<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','friend');
require './includes/common.inc.php';
if(!isset($_COOKIE['username'])){
	_alert_close('清先登陆');
}

if(@$_GET['action'] == 'add'){

	_check_code($_POST['code'],$_SESSION['code']);
	if(!!$_rows = _fetch_array("SELECT 
	                                   tg_uniqid 
	                              FROM 
	                                   tg_user 
                                 WHERE 
	                                   tg_username='{$_COOKIE['username']}'
	                             LIMIT
	                                   1
	  ")){
		  _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
	}
	include './includes/check.func.php';
	$_clean = array();
	$_clean['touser'] = $_POST['touser'];
	$_clean['fromuser']= $_COOKIE['username'];
	$_clean['content'] = _check_content($_POST['content']);
	$_clean = _mysql_string($_clean);
	if($_clean['touser'] == $_clean['fromuser']){
		_alert_close('无法添加自己');
	}
	if(!!$_rows = _fetch_array("SELECT
	                                   tg_id
	                              FROM
	                                   tg_friend
	                             WHERE
	                                   (tg_touser='{$_clean['touser']}' AND tg_fromuser='{$_clean['fromuser']}')
	                                OR
	                                   (tg_touser='{$_clean['fromuser']}' AND tg_fromuser='{$_clean['touser']}')
	                             LIMIT
	                                   1
	")){
		          _alert_close('你们已经是好友了！或者是未验证的好友！无需添加');
	}else{
		_query("INSERT INTO tg_friend(
		                                tg_touser,
		                                tg_fromuser,
		                                tg_content,
		                                tg_date
		                             )
		                       VALUES(
	                                    '{$_clean['touser']}',
	                                    '{$_clean['fromuser']}',
	                                    '{$_clean['content']}',
	                                         NOW()
	                                 )
		");
	    if(_affected_rows() == 1){
	    	_close();
	    	_alert_close('好友添加成功,请等待验证!');
	    }else{
	    	_close();
	    	_alert_close('添加失败');
	    }
	}
	exit();
}

if(isset($_GET['id'])){
  if(!!$_rows = _fetch_array("SELECT 
                                      tg_username 
                                FROM 
                                      tg_user 
                               WHERE 
                                      tg_id='{$_GET['id']}' 
                               LIMIT 
                                      1
  ")){
  	$_html = array();
  	$_html['touser'] = $_rows['tg_username'];
  	$_html = _html($_html);
  }else {
  	_alert_close('你不能这么做');
  }
} else{
  _alert_close('你不能这么做');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
  require './includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js" ></script>
<script type="text/javascript" src="js/message.js" ></script>
</head>

<body>

<div id="message">
  <h3>添加好友</h3>
  <form method="post" action="?action=add">
  <input type="hidden" value="<?php echo $_html['touser']?>" name="touser" />
  <dl>
    <dd><input type="text" readonly="readonly" class="text" value="123" /></dd>
    <dd><textarea name="content" >我想和你交朋友!</textarea></dd>
    <dd>验证码：<input type="text" name="code" class="text yzm" /><img src="code.php" id="code" /><input type="submit" class="submit" value="添加好友"/></dd>
  </dl>
  </form>
</div>

</body>
</html>











