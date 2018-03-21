<?php 
session_start();
//
define('IN_TG',true);
define('SCRIPT','message');
require './includes/common.inc.php';
//判断是否登陆 未登录无法发消息
if(!isset($_COOKIE['username'])){
	_alert_close('清先登陆');
}
//写短信
if(@$_GET['action'] == 'write'){
    _check_code($_POST['code'],$_SESSION['code']);
    //查找发信人用户 
    if(!!$_rows = _fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
            _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
            //引入验证文件
            include './includes/check.func.php';
            
        	$_clean = array();
        	//收信人
        	$_clean['touser'] = $_POST['touser'];
        	//发信人
        	$_clean['fromuser'] = $_COOKIE['username'];
        	//内容
        	$_clean['content'] = _check_content($_POST['content']);
        	//转义数据
        	$_clean = _mysql_string($_clean);
        	//写入信息到数据库
        	_query("INSERT INTO tg_message (
        																	tg_touser,
        																	tg_fromuser,
        																	tg_content,
        																	tg_date
        																	) 
        													VALUES (
        																	'{$_clean['touser']}',
        																	'{$_clean['fromuser']}',
        																	'{$_clean['content']}',
        																	NOW()
        																	)  				    			
        				");
                    if(_affected_rows() == 1){
                    	_close();
                    	//_session_destroy();
                    	_alert_close('发送成功');
                    }else{
                    	_close();
                    	//_session_destroy();
                    	_alert_back('短信发送失败');
                    }
    }else{
    	_alert_close('非法登陆');
    }
}
//获取数据
if(isset($_GET['id'])){
  //查找数据库里面的 收信人ID
  if(!!$_rows = _fetch_array("SELECT tg_username FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1")){
  	$_html = array();
  	$_html['touser'] = $_rows['tg_username'];
  	$_html = _html($_html);
  	if ($_html['touser'] == $_COOKIE['username']) {
  		_alert_close('不能发送自己');
  	}
  }else {
  	_alert_close('不存在此用户！');
  }
} else{
  _alert_close('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
  <h3>写短信</h3>
  <form method="post" action="?action=write">
  <input type="hidden" value="<?php echo $_html['touser']?>" name="touser" />
  <dl>
    <dd><input type="text" readonly="readonly" class="text" value="TO:<?php echo $_html['touser']?>" /></dd>
    <dd><textarea name="content" ></textarea></dd>
    <dd>验证码：<input type="text" name="code" class="text yzm" /><img src="code.php" id="code" /><input type="submit" class="submit" value="发送短信"/></dd>
  </dl>
  </form>
</div>

</body>
</html>











