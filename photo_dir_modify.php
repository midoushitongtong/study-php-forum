<?php
session_start();
//防止includes被恶意调用
define('IN_TG',true);
//css样式表名称
define('SCRIPT','photo_add_dir');
//核心文件引入
require './includes/common.inc.php';
//创建文件函数 mkdir('photo',0777);
//检查文件是否存在 echo is_dir('photo');
//管理员才能啊

_manage_login();





//modify
if (@$_GET['action'] == 'modify') {
	if (!!$_rows = _fetch_array("SELECT
	                                    tg_uniqid
	                               FROM
	                                    tg_user
	                              WHERE
	                                    tg_username='{$_COOKIE['username']}'
	                              LIMIT
	                                    1
	")) {
		 _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		 $_clean = array();
		 include './includes/check.func.php';
		 $_clean['id'] = $_POST['id'];
		 $_clean['name'] = _check_dir_name($_POST['name'],3,20);
		 $_clean['type'] = $_POST['type'];
		 if(!empty($_clean['type'])){
		 	$_clean['password'] = _check_dir_password($_POST['password'],6);
		 }
		 $_clean['face'] = $_POST['face'];
		 $_clean['content'] = $_POST['content'];
		 $_clean = _mysql_string($_clean);
		 if(empty($_clean['type'])){
		 	_query("UPDATE 
		 	              tg_dir
								SET
		 	              tg_name='{$_clean['name']}',
		 	              tg_type='{$_clean['type']}',
		 	              tg_password=NULL,
		 	              tg_face='{$_clean['face']}',
		 	              tg_content='{$_clean['content']}'
		 	         WHERE
		 	              tg_id='{$_clean['id']}'
		 	         LIMIT 
		 	              1
		    ");
		 } else {
		  _query("UPDATE
										 tg_dir
								 SET
										 tg_name='{$_clean['name']}',
										 tg_type='{$_clean['type']}',
										 tg_password='{$_clean['password']}',
										 tg_face='{$_clean['face']}',
										 tg_content='{$_clean['content']}'
							 WHERE
										 tg_id='{$_clean['id']}'
								LIMIT
										 1
		          ");
		  }
		  
		  if (_affected_rows() == 1) {
		  	_close();
		  	_location('ok','photo.php');
		  } else {
		  	_close();
		  	_alert_back('不行');
		  }
		 
	} else {
		_alert_back('你不能这么做');
	}
}



if(isset($_GET['id'])){
  if(!!$_rows = _fetch_array("SELECT
                                     tg_id,
                                     tg_name,
                                     tg_type,
                                     tg_face,
                                     tg_content
                                FROM 
                                     tg_dir 
                               WHERE 
                                     tg_id='{$_GET['id']}'
                               LIMIT
                                     1
  ")){
  	 $_html = array();
  	 $_html['id'] = $_rows['tg_id'];
  	 $_html['name'] = $_rows['tg_name'];
  	 $_html['type'] = $_rows['tg_type'];
  	 $_html['face'] = $_rows['tg_face'];
  	 $_html['content'] = $_rows['tg_content'];
  	 $_html = _html($_html);
  } else {
  	_alert_back('不存在此相册');
  }
} else {
	_alert_back('不能这么做啊');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/photo_add_dir.js"></script>
<?php
require './includes/title.inc.php';
?>

</head>

<body>

<?php 
require './includes/header.inc.php';
?>

<div id="photo">
  <h2>modify相册</h2>
  <form method="post" action="?action=modify">
  <dl>
    <dd>相册名称：<input value="<?php echo $_html['name']?>" type="text" name="name" class="text" /></dd>
    <dd>相册类型：<input type="radio" name="type" id="for_public" value="0" <?php if($_html['type'] == 0)echo 'checked="checked"'?>/> <label for="for_public">公开</label> <input type="radio" name="type" id="for_password" value="1" <?php if($_html['type'] == 1)echo 'checked="checked"'?> /> <label for="for_password">私密</label> </dd>
    <dd id="pass" <?php if($_html['type'] == 1) echo 'style="display:block"'?>>新 密 码 ：<input type="password" name="password" class="text" /></dd>
    <dd>相册封面：<input value="<?php echo $_html['face']?>" type="text" name="face" class="text"></dd>
    <dd>相册描述：<textarea name="content"><?php echo $_html['content']?></textarea></dd>
    <dd><input type="submit" class="submit" value="修改相册啊啊" /></dd>
  </dl>
  <input type="hidden" name="id" class="text" value="<?php echo $_html['id']?>"/>
  </form>

</div>

<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











