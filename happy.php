<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_show');
//核心文件引入
require './includes/common.inc.php';
if (isset($_GET['action']) == 'delect' && isset($_GET['id'])) {
	if (!!@$_rows = _fetch_array("SELECT
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
		                                        tg_username,tg_url,tg_id,tg_sid
		                                   FROM
		                                        tg_photo
		                            WHERE
		                                  tg_id='{$_GET['id']}'
		          LIMIT
		                1
		        
		        ")) {
		        $_html =array();
		        $_html['id'] = $_GET['id'];
		        $_html['sid'] = $_rows['tg_sid'];
		        $_html['username'] = $_rows['tg_username'];
		        $_html['url'] = $_rows['tg_url'];
		        //身份验证操作
		        if ($_html['username'] == @$_COOKIE['username'] || isset($_SESSION['admin'])) {
		          //delete
		          _query("DELETE FROM tg_photo WHERE tg_id='{$_html['id']}'");
		          if (_affected_rows() == 1) {
		            if (file_exists($_html['url'])) {
		              unlink($_html['url']);	               
		            } else {
		              _alert_back('你不能这么做');
		            }
		          	_close();
		          	_location('ok','photo_show.php?id='.$_html['sid']);
		          } else {
		          	_close();
		          	_alert_back('不行');
		          }
		        } else {
		          _alert_back('你不能这么做');
		      } 		    
		    }
	} else {
		_alert_back('你不能这么做');
}
}


//取值
if (isset($_GET['id'])) {
	if (!!$_rows = _fetch_array("SELECT
	                                    tg_id,
	                                    tg_name,tg_type
	                               FROM
	                                    tg_dir
	                              WHERE
	                                    tg_id='{$_GET['id']}'
	                              LIMIT
	                                    1	    		    
	")) {
		$_dirhtml = array();
		$_dirhtml['id'] = $_rows['tg_id'];
		$_dirhtml['name'] = $_rows['tg_name'];
		$_dirhtml['type'] = $_rows['tg_type'];
		$_dirhtml = _html($_dirhtml);
		
		if (@$_POST['password']) {
		    if (!!$_rows = _fetch_array("SELECT 
		                                        tg_id
		                                  FROM
		                                        tg_dir
		                                 WHERE
		                                        tg_password='".sha1($_POST['password'])."'
		                                 LIMIT
		                                        1
		        ")) {
        		//生成
        		setcookie('photo'.$_dirhtml['id'],$_dirhtml['name']);
        		_location(null,'photo_show.php?id='.$_dirhtml['id']);
		    } else {
		    	_alert_back('密码不正确啊');
		    	_location(null.'photo_show.php?id='.$_dirhtml['id']);
		    }
		} 
		
	} else {
		_alert_back('目录不存在啊');
	}
} else {
	_alert_back('目录不存在啊');
}
$_percent = 0.66;
global $_pagesize,$_pagenum,$_system;
$_id = 'id='.$_dirhtml['id'].'&';
_page("SELECT tg_id FROM tg_photo WHERE tg_sid='{$_GET['id']}'",$_system['photo']);
$_result = _query("SELECT
                          tg_id,tg_username,tg_name,tg_url,tg_readcount,tg_commendcount
                     FROM
                          tg_photo
                    WHERE
                          tg_sid='{$_dirhtml['id']}'
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
  <h2><?php echo $_dirhtml['name']?></h2>
  <?php 
    if (empty($_dirhtml['type']) || @$_COOKIE['photo'.$_dirhtml['id']] == $_dirhtml['name'] || isset($_SESSION['admin'])) {
      
    $_html = array();
    while($_rows = _fetch_array_list($_result)){
    $_html['id'] = $_rows['tg_id'];
    $_html['username'] = $_rows['tg_username'];
    $_html['name'] = $_rows['tg_name'];
    $_html['url'] = $_rows['tg_url'];
    $_html['readcount'] = $_rows['tg_readcount'];
    $_html['commendcount'] = $_rows['tg_commendcount'];
    $_html = _html($_html);
  ?>
  
  <dl>
    <dt><a href="photo_detail.php?id=<?php echo $_html['id']?>"><img src="thumb.php?filename=<?php echo $_html['url']?>&percent=<?php echo $_percent?>" /></a></dt>
    <dd><a href="photo_detail.php?id=<?php echo $_html['id']?>"><?php echo $_html['name']?></a></dd>
    <dd>阅 (<strong><?php echo $_html['readcount']?></strong>) 评 (<strong><?php echo $_html['commendcount'] ?></strong>) 上传者：<?php echo $_html['username']?></dd>
    <?php 
      if ($_html['username'] == @$_COOKIE['username'] || isset($_SESSION['admin'])) {
    ?>
    <dd><a href="photo_show.php?action=delete&id=<?php echo $_html['id']?>">[删除]</a></dd>
    <?php }?>
  </dl>
  <?php
    }
    _paging(1);
  ?>
  <p><a href="photo_add_img.php?id=<?php echo $_dirhtml['id']?>">上传图片</a></p>
  <?php 
  } else{
  	echo '<form style="text-align: center; padding: 23px 0;" method="post" action="photo_show.php?id='.$_dirhtml['id'].'">';
  	echo '请输入password:<input type="password" name="password"><input type="submit" value="确认">';
  	echo '</form>';
  }
  ?>
</div>

<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











