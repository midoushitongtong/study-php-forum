<?php
define('IN_TG',true);
define('SCRIPT','face');
require './includes/common.inc.php';
//引入css公共文件
require './includes/title.inc.php';

if(!isset($_COOKIE['username'])){
	_alert_back('请登录');
}
















//上传图片功能
if(@$_GET['action'] == 'up'){
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
		//设置上传图片的类型
		$_files = array('image/jpeg','image/pjieg','image/png','image/x-png','image/gif');
		if (is_array($_files)) {
			if (!in_array($_FILES['userfile']['type'],$_files)) {
				_alert_back('上传的类型必须是JPG/PNG/GIF');
			}
		}
		//错误类型判断
		if ($_FILES['userfile']['error'] > 0) {
			switch ($_FILES['userfile']['error']){
				case 1: _alert_back('上传文件超过预定值1');
				  break;
				case 2: _alert_back('上传文件超过预定值2');
				  break;
				case 3: _alert_back('部分被上传');
				  break;
				case 4: _alert_back('没有任何文件被上传');
				  break;				  
			}
			exit;
		}
		
		//上传大小判断
		if ($_FILES['userfile']['size'] > 100000000) {
			_alert_back('长传不能超过1M');
		}
		
		//获取文件后缀
	    $_n = explode('.',$_FILES['userfile']['name']);
	    $_name = $_POST['dir'].'/'.time().'.'.$_n[1];
		
		//文件位置判断
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			if (!@move_uploaded_file($_FILES['userfile']['tmp_name'],$_name)) {
				_alert_back('长传不成功啊');
			} else {
				//_alert_back('上传成功啊啊');
				echo "<script>alert('长传成功啊');window.opener.document.getElementById('url').value='$_name';window.close();</script>";
			}
		} else {
			_alert_back('文件不存在');
		}
	} else {
		_alert_back('你不能这么做');
	}
}




if (!isset($_GET['dir'])) {
	_alert_back('不能这么上传');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/opener.js" >
</script>
</head>
<body>
<div id="face" style="padding: 23px;">
  <form enctype="multipart/form-data" action="upimg.php?action=up" method="post">
    <!-- 1MB -->
    <input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
    <input type="hidden" name="dir" value="<?php echo $_GET['dir']?>" />
          选择图片：<input type="file" name="userfile" />
    <input type="submit" value="上传" />
  </form>
</div>

</body>
</html>











