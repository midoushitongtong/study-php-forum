<?php
session_start();
//防止includes被恶意调用
define('IN_TG',true);
//css样式表名称
define('SCRIPT','photo_add_img');
//核心文件引入
require './includes/common.inc.php';




if(!isset($_COOKIE['username'])){
	_alert_back('请登录');
}




if (@$_GET['action'] == 'addimg') {
  if (!!$_rows = _fetch_array("SELECT
                                      tg_uniqid
                                 FROM
                                      tg_user
                                WHERE
                                      tg_username='{$_COOKIE['username']}'
                                LIMIT
                                      1"
  )) {
  	 _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
  	 include './includes/check.func.php';
  	 //接收数据啊啊
  	 $_clean = array();
  	 $_clean['name'] = _check_dir_name($_POST['name'],2,20);
  	 $_clean['url'] = _check_photo_url($_POST['url']);
  	 $_clean['content'] = $_POST['content'];
  	 $_clean['sid'] = $_POST['sid'];
  	 $_clean = _mysql_string($_clean);
  	 //进入数据库啊
  	 _query("INSERT INTO tg_photo (
  	                               tg_name,
  	                               tg_url,
  	                               tg_content,
  	                               tg_sid,
  	                               tg_username,
  	                               tg_date   
  	                             ) 
  	                      VALUES (
  	                               '{$_clean['name']}',
  	                               '{$_clean['url']}',
  	                               '{$_clean['content']}',
  	                               '{$_clean['sid']}',
  	                               '{$_COOKIE['username']}',
  	                               NOW()
  	                             )"
  	 );
  	 if (_affected_rows() == 1) {
  	 	_close();
  	 	_location('ok','photo_show.php?id='.$_clean['sid']);
  	 } else {
  	 	_close();
  	 	_alert_back('不行');
  	 }
  } else {
  	_alert_back('你不能这么做');
  }
}

if (isset($_GET['id'])) {
  if (!!$_rows = _fetch_array("SELECT
                                      tg_id,
                                      tg_dir
                                 FROM
                                      tg_dir
                                WHERE
                                      tg_id='{$_GET['id']}'
                                LIMIT
                                      1
      ")) {
          $_html = array();
          $_html['id'] = $_rows['tg_id'];
          $_html['dir'] = $_rows['tg_dir'];
          $_html = _html($_html);
  } else {
    _alert_back('目录不存在啊');
  }
} else {
  _alert_back('目录不存在啊');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/photo_add_img.js"></script>

<?php
require './includes/title.inc.php';
?>

</head>

<body>

<?php
require './includes/header.inc.php';
?>

<div id="photo">
  <h2>上传图片</h2>
  <form method="post" action="?action=addimg">
  <input type="hidden" name="sid" value="<?php echo $_html['id']?>"/>
  <dl>
    <dd>图片名称：<input type="text" name="name" class="text" /></dd>
    <dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text"/><a href="javascript:;" title="<?php echo $_html['dir']?>" id="up">上传</a></dd>
    <dd>相册描述：<textarea name="content"></textarea></dd>
    <dd><input type="submit" class="submit" value="添加图片啊啊"/></dd>
  </dl>
  </form>
</div>

<?php 
require './includes/footer.inc.php';
?> 
</body>
</html>











