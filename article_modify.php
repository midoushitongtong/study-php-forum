<?php
session_start();
//定义常量授权includes下的 公共文件
define('IN_TG',true);
//引入css文件文字
define('SCRIPT','article_modify');
require './includes/common.inc.php';
require './includes/title.inc.php';
//登陆后才能写
if(!isset($_COOKIE['username'])){
	_location('清登陆','login.php');
}
//写入数据库
if(@$_GET['action'] == 'modify'){
	_check_code($_POST['code'],$_SESSION['code']);
	if(!!$_rows =_fetch_array("SELECT
	                                 tg_uniqid
	                             FROM
	                                 tg_user
	                           WHERE
	                                 tg_username='{$_COOKIE['username']}'
	                           LIMIT
	                                 1
	    ")){
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);	
			
		$_clean =array();
		include './includes/check.func.php';
		$_clean['id'] = $_POST['id'];		
		$_clean['type'] = $_POST['type'];
		$_clean['title'] = _check_post_title($_POST['title'],2,20);
		$_clean['content'] = _check_post_content($_POST['content'],2);
		$_clean = _mysql_string($_clean);
		
		if(!!$_rows= _fetch_array("SELECT tg_id,tg_username,tg_title,tg_type,tg_content FROM tg_article WHERE tg_reid = 0 AND tg_id='{$_clean['id']}'")){
		  $_html['username'] = $_rows['tg_username'];		
	      if($_COOKIE['username'] == $_html['username'] || isset($_SESSION['admin'])){
    		//开始修改		    
    		//执行SQL
    		_query("UPDATE tg_article SET 
    		                              tg_type='{$_clean['type']}',
    		                              tg_title='{$_clean['title']}',
    		                              tg_last_modify_date = NOW(),
    		                              tg_content='{$_clean['content']}'
    		                        WHERE
    		                              tg_id='{$_clean['id']}'
    		    
    		    ");
    		//成功或者失败
        		if (_affected_rows() == 1) {
        			_close();
        			_location('修改','article.php?id='.$_clean['id']);
        		} else {
        			_close();
        			_location('失败','article.php?id='.$_clean['id']);
        		}
    		} else {
    		  _alert_back('无权限');
    		}
		} else {
			_alert_back('无权限');
		}
	}else{
	  _alert_back('jh');
	}
}




//读取数据
if(isset($_GET['id'])){
	if(!!$_rows= _fetch_array("SELECT tg_id,tg_username,tg_title,tg_type,tg_content FROM tg_article WHERE tg_reid = 0 AND tg_id='{$_GET['id']}'
	    
	    
	                             ")){
		              //有数据
		              
	                         $_html = array();
	                         $_html['id'] = $_GET['id'];
	                         $_html['username'] = $_rows['tg_username'];
	                         $_html['title'] = $_rows['tg_title'];
	                         $_html['type'] = $_rows['tg_type'];
	                         $_html['content'] = $_rows['tg_content'];  

	                         
	                         //权限
	                         if(!isset($_SESSION['admin'])){
	                           if($_COOKIE['username'] != $_html['username']){
	                         	 _alert_back('无权限');
	                           }
	                         }
	 
	}else{
	  _alert_back('');
	}
}else{
  _alert_back('');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/code.js" ></script>
<script type="text/javascript" src="js/post.js" ></script>
</head>
<body>

<?php 
  require './includes/header.inc.php';
?>

<div id="post">
  <h2>j</h2>
  <form method="post" name ="post" action="?action=modify">
  <input type="hidden" value="<?php echo $_html['id']?>" name="id"></input>
  <input type="hidden" name="uniqid" value="<?php echo $uniqid ?>" />
    <dl>
      <dt>请认真填写以下内容</dt>
      <dd>
                          类　　型：
          <?php 
            foreach (range(1,16) as $_num){
                if($_num == $_html['type']){
            	   echo '<label><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'" checked="checked">';
            	}else{
                   echo '<label><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'">';
                    }
            	echo '<img src="images/icon'.$_num.'.gif" alt="类型"></label>';
            	if($_num == 8){
            		echo '</br>　　　　　 ';
            	}
            }
          ?>
      </dd>
      <dd>标　　题：<input type="text" name="title" value="<?php echo $_html['title']?>" class="text" /><em>(*必填，至少两位)</em></dd>
      <dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a>　<a href="javascript:;">Q图系列[2]</a>　<a href="javascript:;">Q图系列[3]</a></dd>
        
      <dd>
        <?php 
          include './includes/ubb.inc.php';
        ?>
        <textarea name="content" rows="9"><?php echo $_html['content']?></textarea>
      </dd>
      <dd>验证码　：<input type="text" name="code" class="text yzm" /><img src="code.php" id="code" /><input type="submit" class="submit" value="修改"/></dd>
    </dl>
  </form>
</div>

<?php
  require './includes/footer.inc.php';  
?>
</body>
</html>