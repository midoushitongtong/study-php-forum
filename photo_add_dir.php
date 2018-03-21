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










if(@$_GET['action'] == 'adddir'){
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
	    
	    //验证完毕接受
	    include './includes/check.func.php';
	    $_clean = array();
	    $_clean['name'] = _check_dir_name($_POST['name'],3,26);
	    $_clean['type'] = $_POST['type'];
	    if(!empty($_clean['type'])){
	       $_clean['password'] = _check_dir_password($_POST['password'],6);
	    }
	    $_clean['content'] = $_POST['content'];
	    $_clean['dir'] = time();
	    $_clean = _mysql_string($_clean);
	    
	    //检查主目录 没有的话创建
	    if (!is_dir('photo')) {
	        mkdir('photo',0777);
	    }
	    //在主目录在创建相册目录
	    if (!is_dir('photo/'.$_clean['dir'])){
	        mkdir('photo/'.$_clean['dir']);
	    }
	    
	    //当前信息添加数据库
	    if (empty($_clean['type'])) {
	    	_query("INSERT INTO tg_dir (
	    	                              tg_name,
	    	                              tg_type,
	    	                              tg_content,
	    	                              tg_dir,
	    	                              tg_date
	                                   ) 
	    	                    VALUES (
	    	                              '{$_clean['name']}',
	    	                              '{$_clean['type']}',
	    	                              '{$_clean['content']}',
	    	                              'photo/{$_clean['dir']}',
	    	                              NOW()
	                                   )
	    ");
	    } else {
	    	_query("INSERT INTO tg_dir (
	    	                              tg_name,
	    	                              tg_type,
	    	                              tg_password,
	    	                              tg_content,
	    	                              tg_dir,
	    	                              tg_date
	                                   ) 
	    	                    VALUES (
	    	                              '{$_clean['name']}',
	    	                              '{$_clean['type']}',
	    	                              '{$_clean['password']}',
	    	                              '{$_clean['content']}',
	    	                              'photo/{$_clean['dir']}',
	    	                              NOW()
	                                   )
	    ");
	    }
	    
	    //目录添加成功
	    if (_affected_rows() == 1) {
	    	_close();
	    	_location('OK','photo.php');
	    } else {
	    	_close();
	    	_alert_back('不行');
	    }
	    
	} else {
		_alert_back('你不能这么做');
	}
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
  <h2>添加相册</h2>
  <form method="post" action="?action=adddir">
  <dl>
    <dd>相册名称：<input type="text" name="name" class="text" /></dd>
    <dd>相册类型：<input type="radio" name="type" id="for_public" value="0" checked="checked" /> <label for="for_public">公开</label> <input type="radio" name="type" id="for_password" value="1" /> <label for="for_password">私密</label> </dd>
    <dd id="pass">相册密码：<input type="password" name="password" class="text" /></dd>
    <dd>相册描述：<textarea name="content"></textarea></dd>
    <dd><input type="submit" class="submit" value="添加相册啊啊" /></dd>
  </dl>
  </form>
</div>

<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











