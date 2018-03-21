<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','member_modify');
require './includes/common.inc.php';
//修改资料
if(@$_GET['action'] == 'modify'){
    //验证客户端和服务端的验证码是否一致
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows = _fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
    	//防止cookie伪造，还要比对唯一标示符uniqid()
    	_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
    	//引入验证文件
    	include './includes/check.func.php';
    	//创建验证信息
    	
    	
    	
    	$_clean = array();
    	$_clean['password'] = _check_modify_password($_POST['password'],6);
    	$_clean['sex'] = _check_sex($_POST['sex']);
    	$_clean['face'] = _check_face($_POST['face']);
    	$_clean['email'] = _check_email($_POST['email'],2,33);
    	$_clean['qq'] = _check_qq($_POST['qq']);
    	$_clean['url'] = _check_url($_POST['url'],33);
    	$_clean['switch'] = $_POST['switch'];
    	$_clean['autograph'] = _check_autograph($_POST['autograph'],200);
    	
    	//修改资料
    	if(empty($_clean['password'])){
    		_query("UPDATE tg_user SET 
    		                          tg_sex='{$_clean['sex']}',
    		                          tg_face='{$_clean['face']}',
    		                          tg_email='{$_clean['email']}',
    		                          tg_qq='{$_clean['qq']}',
    		                          tg_url='{$_clean['url']}',
    		                          tg_switch='{$_clean['switch']}',
    		                          tg_autograph='{$_clean['autograph']}'
    	                          WHERE
    		                          tg_username='{$_COOKIE['username']}'
    		                          ");
        
    	} else{
    	  _query("UPDATE tg_user SET
                                  	  tg_password='{$_clean['password']}',
                                  	  tg_sex='{$_clean['sex']}',
                                  	  tg_face='{$_clean['face']}',
                                  	  tg_email='{$_clean['email']}',
                                  	  tg_qq='{$_clean['qq']}',
                                  	  tg_url='{$_clean['url']}',
                                  	  tg_switch='{$_clean['switch']}',
    		                          tg_autograph='{$_clean['autograph']}'
                              	  WHERE
                                  	  tg_username='{$_COOKIE['username']}'
    	                              ");
    	}
	}
	//判断是否修改成功
	if(_affected_rows() == 1){
		_close();
		//_session_destroy();
		_location('修改成功','member.php');
	}else{
		_close();
		//_session_destroy();
		_location(null,'member_modify.php');
	}
}
//是否正常登陆,才允许进入本页面
if(isset($_COOKIE['username'])){
  //获取数据
  $_rows = _fetch_array("SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_switch,tg_autograph FROM tg_user WHERE tg_username='{$_COOKIE['username']}'");
  //判断数据是否存在
  if($_rows){
  	$_html =array();
  	$_html['username'] = _html($_rows['tg_username']);
  	$_html['sex'] = _html($_rows['tg_sex']);
  	$_html['face'] = _html($_rows['tg_face']);
  	$_html['email'] = _html($_rows['tg_email']);
  	$_html['url'] = _html($_rows['tg_url']);
  	$_html['qq'] = _html($_rows['tg_qq']);
  	$_html['switch'] = _html($_rows['tg_switch']);
  	$_html['autograph'] = _html($_rows['tg_autograph']);
  	//性别选择
  	if($_html['sex'] == '男'){
  	   $_html['sex_html'] = '<input type="radio" name="sex" value="男" checked="checked" /> 男 <input type="radio" name="sex" value="女" /> 女 ';
  	}elseif($_html['sex'] == '女'){
  	   $_html['sex_html'] = '<input type="radio" name="sex" value="男" /> 男 <input type="radio" name="sex" value="女" checked="checked" /> 女 ';
  	}
  	
  	//头像选择
  	$_html['face_html'] = '<select name="face" >';
  	 foreach (range(1,9) as $_num){
  	   if ($_html['face'] == 'face/m0'.$_num.'.gif') {  
  	     $_html['face_html'] .= '<option value="face/m0'.$_num.'.gif" selected="selected">face/m'.$_num.'.gif</option>';
  	   } else {
  	     $_html['face_html'] .= '<option value="face/m0'.$_num.'.gif">face/m'.$_num.'.gif</option>';
  	   }
  	 }
  	 foreach (range(10,64) as $_num){
  	   if ($_html['face'] == 'face/m'.$_num.'.gif') {
  	     $_html['face_html'] .= '<option value="face/m'.$_num.'.gif" selected="selected">face/m'.$_num.'.gif</option>';
  	   } else {
  	   	 $_html['face_html'] .= '<option value="face/m'.$_num.'.gif">face/m'.$_num.'.gif</option>';
  	   }
  	 }
  	$_html['face_html'] .= '</select>';
  	
  	//见面
  	if($_html['switch'] == 1){
  	  $_html['switch_html'] = '<input type="radio" value="1" name="switch" checked="checked">启用 <input type="radio" value="0" name="switch" >不用';
  	}elseif($_html['switch'] == 0){
  	  $_html['switch_html'] = '<input type="radio" value="1" name="switch" >启用 <input type="radio" value="0" name="switch" checked="checked">不用';
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
<script type="text/javascript" src="js/code.js" ></script>
<script type="text/javascript" src="js/member_modify.js" ></script>
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
    <form method="post" action="?action=modify"/>
    <dl>
      <dd>用 户 名 ：<?php echo $_html['username']?></dd>
      <dd>密　　码：<input type="password" name="password" class="text"/><em>(*留空不修改)</em></dd>
      <dd><?php echo $_html['sex_html']?></dd>
      <dd>头　　像：<?php echo $_html['face_html']?></dd>
      <dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $_html['email']?>" /></dd>
      <dd>主　　页：<input type="text" class="text" name="url" value="<?php echo $_html['url'] ?>" /></dd>
      <dd>Q 　　Q：<input type="text" class="text" name="qq" value="<?php echo $_html['qq']?>" /></dd>
      <dd>个性签m：<?php echo $_html['switch_html']?>
        <p><textarea name="autograph"><?php echo $_html['autograph']?></textarea></p>
      </dd>
      <dd>验 证 码 ：<input type="text" class="text yzm" name="code"/><img src="code.php" id="code"/><input type="submit" class="submit" value="修改资料" /></dd>
    </dl>
    </form>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>