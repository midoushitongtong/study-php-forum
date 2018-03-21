<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','manage_set');
require './includes/common.inc.php';
_manage_login();

//接受数据
if (@$_GET['action'] == 'set') {
	if(!!$_rows = _fetch_array("SELECT
	                                  tg_uniqid
	                              FROM
	                                  tg_user
	                             WHERE
	                                  tg_username='{$_COOKIE['username']}'
	                             LIMIT
	                                  1"
	    
	    )){
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		$_clean = array();
		$_clean['webname'] = $_POST['webname'];
		$_clean['article'] = $_POST['article'];
		$_clean['blog'] = $_POST['blog'];
		$_clean['photo'] = $_POST['photo'];
		$_clean['skin'] = $_POST['skin'];
		$_clean['string'] = $_POST['string'];
		$_clean['post'] = $_POST['post'];
		$_clean['re'] = $_POST['re'];
		$_clean['code'] = $_POST['code'];
		$_clean['register'] = $_POST['register'];
		$_clean = _mysql_string($_clean);
		
		//接受完 进入数据库啊
		
		_query("UPDATE tg_system SET 
		                            tg_webname='{$_clean['webname']}',
		                            tg_article='{$_clean['article']}',
		                            tg_blog='{$_clean['blog']}',
		                            tg_photo='{$_clean['photo']}',
		                            tg_skin='{$_clean['skin']}',
		                            tg_string='{$_clean['string']}',
		                            tg_post='{$_clean['post']}',
		                            tg_re='{$_clean['re']}',
		                            tg_code='{$_clean['code']}',
		                            tg_register='{$_clean['register']}'
		                       WHERE
		                            tg_id=1
		                       LIMIT
		                            1
		    ");
		//是否成功或者失败啊啊啊啊啊
		if(_affected_rows() == 1){
			_close();
			_location('修改成功啊啊','manage.php');
		}else{
			_close();
			_location('没数据被修改','manage.php');
		}		
	} else {
	  _alert_back('你不是管理员');
	}
}
//读取系统数据
if(!!$_rows = _fetch_array("SELECT
                                  tg_webname,
                                  tg_article,
                                  tg_blog,
                                  tg_photo,
                                  tg_skin,
                                  tg_string,
                                  tg_post,
                                  tg_re,
                                  tg_code,
                                  tg_register                                  
                              FROM
                                  tg_system
                             WHERE
                                  tg_id=1
                             LIMIT
                                  1"
)){
  $_html = array();
  $_html['webname'] = $_rows['tg_webname'];
  $_html['article'] = $_rows['tg_article'];
  $_html['blog'] = $_rows['tg_blog'];
  $_html['photo'] = $_rows['tg_photo'];
  $_html['skin'] = $_rows['tg_skin'];
  $_html['string'] = $_rows['tg_string'];
  $_html['post'] = $_rows['tg_post'];
  $_html['re'] = $_rows['tg_re'];
  $_html['code'] = $_rows['tg_code'];
  $_html['register'] = $_rows['tg_register'];
  $_html = _html($_html);
  
  //判断文章啊啊
  if($_html['article'] == 10){
  	 $_html['article_html'] = '<select name="article"><option value="10" seletced="selected">每页十篇文章</option><option value="15">每页十五篇文章</option></select>';
  }elseif($_html['article'] == 15){
  	 $_html['article_html'] = '<select name="article"><option value="10">每页十篇文章</option><option value="15" selected="selected">每页十五篇文章</option></select>';
  }
  //判断博友啊啊
  if($_html['blog'] == 15){
    $_html['blog_html'] = '<select name="blog"><option value="15" seletced="selected">每页十五个人</option><option value="20">每页二十个人</option></select>';
  }elseif($_html['blog'] == 20){
    $_html['blog_html'] = '<select name="blog"><option value="15">每页十五个人</option><option value="20" selected="selected">每页二十个人</option></select>';
  }
  //判断相册啊啊
  if($_html['photo'] == 8){
    $_html['photo_html'] = '<select name="photo"><option value="8" seletced="selected">每页八张照片</option><option value="12">每页十二张照片</option></select>';
  }elseif($_html['photo'] == 12){
    $_html['photo_html'] = '<select name="photo"><option value="8">每页八张照片</option><option value="12" selected="selected">每页十二张照片</option></select>';
  }
  //判断皮肤啊啊{
  if($_html['skin'] == 1){
  	 $_html['skin_html'] = '<select name="skin"><option value="1" selected="selected">第一套皮肤啊</option><option value="2">第二套皮肤啊</option><option value="3">第三套皮肤啊</option></select>';
  }elseif($_html['skin'] == 2){
  	 $_html['skin_html'] = '<select name="skin"><option value="1">第一套皮肤啊</option><option value="2" selected="selected">第二套皮肤啊</option><option value="3">第三套皮肤啊</option></select>';
  }elseif($_html['skin'] == 3){
     $_html['skin_html'] = '<select name="skin"><option value="1">第一套皮肤啊</option><option value="2">第二套皮肤啊</option><option value="3" selected="selected">第三套皮肤啊</option></select>';    
  }
  //判断发帖啊啊
  if($_html['post'] == 30){
  	 $_html['post_html'] = '<input type="radio" name="post" value="30" checked="checked">30秒 <input type="radio" name="post" value="60">1分钟 <input type="radio" name="post" value="180">3分钟';
  }elseif($_html['post'] == 60){
     $_html['post_html'] = '<input type="radio" name="post" value="30">30秒 <input type="radio" name="post" value="60" checked="checked">1分钟 <input type="radio" name="post" value="180">3分钟';   
  }elseif($_html['post'] == 180){
     $_html['post_html'] = '<input type="radio" name="post" value="30">30秒 <input type="radio" name="post" value="60">1分钟 <input type="radio" name="post" value="180" checked="checked">3分钟';   
  }
  //判断回复啊啊
  if($_html['re'] == 15){
    $_html['re_html'] = '<input type="radio" name="re" value="15" checked="checked">15秒 <input type="radio" name="re" value="30">30秒 <input type="radio" name="re" value="45">45秒';
  }elseif($_html['re'] == 30){
    $_html['re_html'] = '<input type="radio" name="re" value="15">15秒 <input type="radio" name="re" value="30" checked="checked">30秒 <input type="radio" name="re" value="45">45秒';
  }elseif($_html['re'] == 45){
    $_html['re_html'] = '<input type="radio" name="re" value="15">15秒 <input type="radio" name="re" value="30">30秒 <input type="radio" name="re" value="45" checked="checked">45秒';
  }
  //判断验证开启
  if($_html['code'] == 1){
  	 $_html['code_html'] = '<input value="1" type="radio" name="code" checked="checked"> 启用 <input value="0" type="radio" name="code"> 不启用';
  }else{
     $_html['code_html'] = '<input value="1" type="radio" name="code"> 启用 <input value="0" type="radio" name="code" checked="checked" checked="checked"> 不启用';    
  }
  //判断开放注册
  if ($_html['register']) {
  	 $_html['register_html'] = '<input value="1" type="radio" name="register" checked="checked"> 开放 <input value="0" type="radio" name="register"> 不开放';
  } else {
     $_html['register_html'] = '<input value="1" type="radio" name="register"> 开放 <input value="0" type="radio" name="register" checked="checked"> 不开放';    
  }
}else{
  _alert_back('系统数据读取错误');
}
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

<div id="member">
  <?php 
  require './includes/manage.inc.php';
  ?>
  <div id="member_main">
    <h2>会员管理中心</h2>
    <form method="post" action="?action=set">
    <dl>
      <dd>网　站　名　称 : <input type="text" name="webname" class="text" value="<?php echo $_html['webname']?>"></dd>
      <dd>文章每页列表数 : <?php echo $_html['article_html']?></dd>
      <dd>博客每页列表数 : <?php echo $_html['blog_html']?></dd>
      <dd>相册每页列表数 : <?php echo $_html['photo_html']?></dd>
      <dd>站 点 默认 皮 肤 : <?php echo $_html['skin_html']?></dd>
      <dd>字 符 过滤 啊 啊 : <input type="text" name="string" class="text" value="<?php echo $_html['string']?>"> (请用 | 隔开)</dd>
      <dd>每 次 发帖 时 间 : <?php echo $_html['post_html']?></dd>
      <dd>每 次 回复 时 间 : <?php echo $_html['re_html']?></dd>
      <dd>是否启用验证码 : <?php echo $_html['code_html']?></dd>
      <dd>是 否 开放 注 册 : <?php echo $_html['register_html']?></dd>
      <dd><input type="submit" value="修改" class="submit"></dd>
    </dl>
    </form>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>