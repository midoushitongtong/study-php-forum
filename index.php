<?php
session_start();
//定义常量授权includes下的 公共文件
define('IN_TG',true);
//引入css文件
define('SCRIPT','index');
require './includes/common.inc.php';
require './includes/title.inc.php';
//输出xml的值  <!-- global.func.php -->
//读取html文件
$_html = _get_xml('new.xml');
//输出xml的值
//读取帖子列表
global $_pagesize,$_pagenum,$_system;
_page("SELECT tg_id FROM tg_article WHERE tg_reid = 0",$_system['article']);
$_result = _query("SELECT
                         tg_id,tg_title,tg_type,tg_readcount,tg_commendcount
                     FROM
                         tg_article
                    WHERE
                         tg_reid = 0
                 ORDER BY
                         tg_date DESC
                    LIMIT
                         $_pagenum,$_pagesize
    ");
$_photo = _fetch_array("SELECT 
                              tg_id AS id,
                              tg_name AS name,
                              tg_url AS url 
                          FROM
                              tg_photo 
                         WHERE
                              tg_sid in (SELECT tg_id FROM tg_dir WHERE tg_type=0)
                      ORDER BY 
                              tg_date DESC 
                         LIMIT
                                     1
");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>

<?php
  require './includes/header.inc.php'; 
?>

<div id="list">
  <h2>帖子列表</h2>
  <a href="post.php" class="post"></a>
  <ul class="article">
    <?php 
      $_htmllist = array();     
      while(!!$_rows = _fetch_array_list($_result)){
      $_htmllist['id'] = $_rows['tg_id'];
      $_htmllsit['type'] = $_rows['tg_type'];
      $_htmllist['readcount'] = $_rows['tg_readcount'];
      $_htmllist['commendcount'] = $_rows['tg_commendcount'];
      $_htmllist['title'] = $_rows['tg_title'];
      $_htmllist = _html($_htmllist);                            
    	echo '<li class="icon'.$_htmllsit['type'].'"><em> 阅读数 (<strong>'.$_htmllist['readcount'].'</strong>) 评论数 (<strong>'.$_htmllist['commendcount'].'</strong>)</em><a class="article" href="article.php?id='.$_htmllist['id'].'">'._title($_htmllist['title'],30).'</a></li>';
      }
      _free_result($_result);
    ?>
  </ul>
  <?php echo _paging(1);?>
</div>
<div id="user">
  <h2>新建会员</h2>
  <dl>
    <dd class="user"><?php echo $_html['username'] ?>(<?php echo $_html['sex']?>)</dd>
    <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username']?>" /></dt>
    <dd class="message"><a href="#" name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
    <dd class="friend"><a href="#" name="friend" title="<?php echo $_html['id']?>">加为好友</a></dd>
    <dd class="guest">写留言</dd>
    <dd class="flower"><a href="#" name="flower" title="<?php echo $_html['id']?>">给他送花</a></dd>
    <dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email']?></a>  </dd>
    <dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_black"><?php echo $_html['url']?></a></dd>
  </dl>
</div>
<div id="pics">
  <h2>最新图片: <?php echo $_photo['name']?></h2>
  <a href="photo_detail.php?id=<?php echo $_photo['id']?>"><img style="display:block;margin:0 auto; max-width: 130px; max-height: 233px; padding: 23px 0;" src="thumb.php?filename=<?php echo $_photo['url']?>&percent=1  " alt="<?php echo $_photo['name']?>"></img></a>
</div>
<?php
  require './includes/footer.inc.php';  
?>

</body>
</html>









