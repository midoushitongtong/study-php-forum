<?php
session_start();
define('IN_TG',true);
define('SCRIPT','blog');
//核心文件引入
require './includes/common.inc.php';
//分页模块
//调用 global.fun.php 函数库里面的函数_page("SELECT tg_id FROM tg_user",15);//
//访问blogo.php 进入page分页为1 
global $_pagesize,$_pagenum,$_system;
_page("SELECT tg_id FROM tg_user",$_system['blog']);
//从数据库中获取结果集
$_result = _query("SELECT 
                          tg_id,tg_username,tg_sex,tg_face 
                     FROM 
                          tg_user 
                 ORDER BY 
                          tg_reg_time 
                          DESC 
                    LIMIT 
                          $_pagenum,$_pagesize
                 ");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="js/blog.js" ></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
</head>

<body>

<?php 
require './includes/header.inc.php';
?>
<div id="blog">
  <h2>博友列表</h2>
  <?php 
        $_html = array();
		while (!!$_rows = _fetch_array_list($_result)) {
			$_html['id'] = $_rows['tg_id'];
			$_html['username'] = $_rows['tg_username'];
			$_html['face'] = $_rows['tg_face'];
			$_html['sex'] = $_rows['tg_sex'];
			$_html = _html($_html);
			//防止输入 html 代码$_html = _html($_html);
	?>
  <dl>
  
    <dd class="user"><?php echo $_rows['tg_username']?>(<?php echo $_rows['tg_sex']?>)</dd>
    <dt><img src="<?php echo $_rows['tg_face']?>" /></dt>
    <dd class="message"><a href="#" name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
    <dd class="friend"><a href="#" name="friend" title="<?php echo $_html['id']?>">加为好友</a></dd>
    <dd class="guest">写留言</dd>
    <dd class="flower"><a href="#" name="flower" title="<?php echo $_html['id']?>">给他送花</a></dd>
  </dl>
  <?php }?>
  
  <div id="page_num">
    <ul>
      <?php for($i=0;$i<$_pageabsolute;$i++){
        if($_page == ($i+1)){
        	echo '<li><a href="blog.php?page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
        }else{
            echo '<li><a href="blog.php?page='.($i+1).'">'.($i+1).'</a></li>';
        }
      }?>
    </ul>
  </div>
  <div id="page_text">
    <ul>
       <li><?php echo $_page?>/<?php echo $_pageabsolute?> 页| </li>
       <li>共有<strong><?php echo $_num?></strong> 个会员 </li>
       <?php
        _free_result($_result);
        if($_page == 1){
        	echo '<li>首页 | </li>';
        	echo '<li>上一页 | </li>';
        }else{
            //别的页面调用的时候自动获取当前路径
            //PHP自带的函数 来连接目录SCRIPT = $_SERVER['SCRIPT_NAME'];
            //自定义的方法来连接目录
        	echo '<li><a href="'.SCRIPT.'.php">首页</a> | </li>';
        	echo '<li><a href="'.SCRIPT.'.php?page='.($_page-1).'">上一页</a> | </li>';
        }
        if($_page == $_pageabsolute){
        	echo '<li>下一页 | </li>';
        	echo '<li>尾页| </li>';
        }else{
        	echo '<li><a href="'.SCRIPT.'.php?page='.($_page+1).'">下一页</a> | </li>';
        	echo '<li><a href="'.SCRIPT.'.php?page='.($_pageabsolute).'">尾页</a> | </li>';
        }
       ?>
    </ul>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











