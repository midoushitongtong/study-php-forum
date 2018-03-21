<?php
session_start();
//定义常量授权includes下的 公共文件
define('IN_TG',true);
//引入css文件文字
define('SCRIPT','post');
require './includes/common.inc.php';
require './includes/title.inc.php';

//登陆后才能写
if(!isset($_COOKIE['username'])){
	_location('清登陆','login.php');
}
//写入数据库
if(@$_GET['action'] == 'post'){
  //验证验证码
  _check_code($_POST['code'],$_SESSION['code']);
  //验证
  if(!!$_rows = _fetch_array("SELECT
                                     tg_uniqid,
                                     tg_post_time
                                FROM
                                     tg_user
                               WHERE
                                     tg_username='{$_COOKIE['username']}'
                               LIMIT
                                     1    
                              ")){
                        global $_system;
                        //找到数据后验证
                        _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']); 
                        //限时
                        _timed(time(),$_rows['tg_post_time'],$_system['post']);
                        //echo '<script>alert("'.$_system['post'].'")</script>';
                        //接受帖子内容
                        //引入验证文件
                        include './includes/check.func.php';
                        $_clean = array();
                        $_clean['username'] = $_COOKIE['username'];
                        $_clean['type'] = $_POST['type'];
                        $_clean['title'] = _check_post_title($_POST['title'],2,20);
                        $_clean['content'] = _check_post_content($_POST['content'],3);
                        $_clean = _mysql_string($_clean);
                        //print_r($_clean);
                        //写入
                        _query("INSERT INTO tg_article(
                                                       tg_username,
                                                       tg_title,
                                                       tg_type,
                                                       tg_content,
                                                       tg_date                                                    )
                                                VALUES(
                                                       '{$_clean['username']}',
                                                       '{$_clean['title']}',
                                                       '{$_clean['type']}',
                                                       '{$_clean['content']}',
                                                       NOW()                 
                                                      )                       
                            
                        ");
                        //判断是否成功
                        if(_affected_rows() ==1 ){
                        	$_clean['id'] = _insert_id();
                        	//setcookie('post_time',time());
                        	$_clean['time'] = time();
                        	_query("UPDATE tg_user SET tg_post_time='{$_clean['time']}' WHERE tg_username='{$_COOKIE['username']}'");
                        	_close();
                        	//_session_destroy();
                        	_location('发表成功','article.php?id='.$_clean['id']);
                        }else{
                            _close();
                            //_session_destroy();
                            _alert_back('发表失败');
                        }
  }
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
  <h2>发表帖子</h2>
  <form method="post" name ="post" action="?action=post">
  <input type="hidden" name="uniqid" value="<?php echo $uniqid ?>" />
    <dl>
      <dt>请认真填写以下内容</dt>
      <dd>
                          类　　型：
          <?php 
            foreach (range(1,16) as $_num){
                if($_num == 1){
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
      <dd>标　　题：<input type="text" name="title" class="text" /><em>(*必填，至少两位)</em></dd>
      <dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a>　<a href="javascript:;">Q图系列[2]</a>　<a href="javascript:;">Q图系列[3]</a></dd>
        
      <dd>
        <?php 
          include './includes/ubb.inc.php';
        ?>
        <textarea name="content" rows="9"></textarea>
      </dd>
      <dd>验证码　：<input type="text" name="code" class="text yzm" /><img src="code.php" id="code" /><input type="submit" class="submit" value="发贴"/></dd>
    </dl>
  </form>
</div>

<?php
  require './includes/footer.inc.php';  
?>
</body>
</html>