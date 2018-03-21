<?php
session_start();
//防止includes被恶意调用
define('IN_TG',true);
//css样式表名称
define('SCRIPT','photo');
//核心文件引入
require './includes/common.inc.php';




if (@$_GET['action'] == 'delete' && isset($_GET['id'])) {
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
		    if (!!$_rows = _fetch_array("SELECT
		                                        tg_id,tg_dir
		                                  FROM
		                                        tg_dir
		                                WHERE
		                                        tg_id='{$_GET['id']}'
		                          LIMIT
		                                1
		        ")) {
		        $_html = array();
		        $_html['url'] = $_rows['tg_dir'];
		        $_html = _html($_html);
  		        if (file_exists($_html['url'])) {
  		          _query("DELETE FROM tg_photo WHERE tg_sid='{$_GET['id']}'");
  		          _query("DELETE FROM tg_dir WHERE tg_id='{$_GET['id']}'");
  		        	_remove_dir($_html['url']);
  		        	_close();
  		        	_location('ok','photo.php');
  		        } else {
  		        	_alert_back("可以");
  		        }
		    } else {
		    	_alert_back("不行");
		    }
	} else {
		_alert_back('你不能这么做');
	}
}
//读取数据啊啊
global $_pagezie,$_pagenum,$_system;
_page("SELECT tg_id FROM tg_dir",13);
$_result = _query("SELECT
              tg_id,tg_name,tg_type,tg_face
          FROM
              tg_dir
      ORDER BY 
              tg_date DESC
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
</head>

<body>

<?php 
require './includes/header.inc.php';
?>
<div id="photo">
  <h2>相册列表</h2>
  <?php
    $_html = array();
    while($_rows = _fetch_array_list($_result)){
    $_html['id'] = $_rows['tg_id'];
    $_html['name'] = $_rows['tg_name'];
    $_html['type'] = $_rows['tg_type'];
    $_html['face'] = $_rows['tg_face'];
    if(empty($_html['type'])){
    	$_html['type_html'] = '(公开)';
    }else{
    	$_html['type_html'] = '(加密)';
    }
    if(empty($_html['face'])){
    	$_html['face_html'] = '';
    }else{
    	$_html['face_html'] = '<img src="'.$_html['face'].'" alt="'.$_html['name'].'">';
    }
    $_html['photo'] = _fetch_array("SELECT COUNT(*) AS count FROM tg_photo WHERE tg_sid='{$_html['id']}'");
  ?>
  <dl>
    <dt><a href="photo_show.php?id=<?php echo $_html['id']?>"><?php echo $_html['face_html']?></a></dt>
    <dd><a href="photo_show.php?id=<?php echo $_html['id']?>"><?php echo $_html['name']?> <span><?php echo '['.$_html['photo']['count'].']',$_html['type_html']?></span></a></dd>
    <?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])){?>
    <dd>[<a href="photo_dir_modify.php?id=<?php echo $_html['id']?>">修改</a>] [<a href="photo.php?action=delete&id=<?php echo $_html['id']?>">删除</a>]</dd>
    <?php }?>
  </dl>
  <?php }?>
  <?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])){?>
  <p><a href="photo_add_dir.php">添加目录</a></p>
  <?php }?>
</div>

<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











