<?php
session_start();
//防止includes被恶意调用
define('IN_TG',true);
//css样式表名称
define('SCRIPT','photo_detail');
//核心文件引入
require './includes/common.inc.php';

if (@$_GET['action'] == 'rephoto') {
  _check_code($_POST['code'],$_SESSION['code']);
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
     $_clean = array();
     $_clean['sid'] = $_POST['sid'];
     $_clean['title'] = $_POST['title'];
     $_clean['username'] = $_COOKIE['username'];
     $_clean['content'] = $_POST['content'];
     $_clean = _mysql_string($_clean);
     _query("INSERT INTO tg_photo_commend
                                          (
                                            tg_title,
                                            tg_content,
                                            tg_sid,
                                            tg_username,
                                            tg_date
                                          )
                                    VALUES(
                                            '{$_clean['title']}',
                                            '{$_clean['content']}',
                                            '{$_clean['sid']}',
                                            '{$_clean['username']}',
                                            NOW()
                                          )"    
     );
     if (_affected_rows() == 1) {
        _query("UPDATE tg_photo SET tg_commendcount=tg_commendcount+1 WHERE tg_id='{$_clean['sid']}'");
        _close();
        _location('hao','photo_detail.php?id='.$_clean['sid']);
     } else {
     	_close();
     	_alert_back('no');
     }
  } else {
  	_alert_back('你不能这么做啊');
  }
}

if (isset($_GET['id'])) {
	if (!!$_rows = _fetch_array("SELECT
	                                    tg_id,
	                                    tg_sid,
	                                    tg_name,
	                                    tg_url,
	                                    tg_username,
	                                    tg_readcount,
	                                    tg_commendcount,
	                                    tg_date,
	                                    tg_content
	                               FROM
	                                    tg_photo
	                              WHERE
	                                    tg_id='{$_GET['id']}'
	                              LIMIT
	                                    1	    		    
	")) {
	     //防止图片穿插访问
	     //取得这个图片的sid，也就是目录
	     //判断是否加密
	     //如果加密，cookie对应是否存在
	     //管理严不受限制
            if (!isset($_SESSION['admin'])) {
              if (!!$_dirs = _fetch_array("SELECT tg_id,tg_type,tg_name FROM tg_dir WHERE tg_id='{$_rows['tg_sid']}'")) {
                  if (!empty($_dirs['tg_type']) && @$_COOKIE['photo'.$_dirs['tg_id']] != $_dirs['tg_name']) {
                  	_alert_back('你不能这么做呢');
                  }
                } else {
                    _alert_back('相册目录不存在请检查数据库');
              }
            }
	//阅读量增加1
	_query("UPDATE tg_photo SET tg_readcount=tg_readcount+1 WHERE tg_id='{$_GET['id']}'");
		$_html = array();
		$_html['id'] = $_rows['tg_id'];
		$_html['sid'] = $_rows['tg_sid'];
		$_html['name'] = $_rows['tg_name'];
		$_html['url'] = $_rows['tg_url'];
		$_html['username'] = $_rows['tg_username'];
		$_html['readcount'] = $_rows['tg_readcount'];
		$_html['commendcount'] = $_rows['tg_commendcount'];
		$_html['date'] = $_rows['tg_date'];
		$_html['content'] = $_rows['tg_content'];
		$_html = _html($_html);
		global $_id;
		$_id = 'id='.$_html['id'].'&';
		//读取评论
		global $_pagesize,$_pagenum,$_page;
		_page("SELECT tg_id FROM tg_photo_commend WHERE tg_sid='{$_html['id']}'",13);
		$_result = _query("SELECT
		                          tg_username,tg_content,tg_title,tg_date
		                     FROM
		                          tg_photo_commend
		                    WHERE
		                          tg_sid='{$_html['id']}'
		                 ORDER BY
		                          tg_date ASC
		                    LIMIT
		                          $_pagenum,$_pagesize
		    ");
		
		//上一页
		$_html['preid'] = _fetch_array("SELECT 
                                    		    min(tg_id) 
                                    		AS 
                                    		    id 
                                    	  FROM 
                                    		    tg_photo
                                    	 WHERE 
		                                        tg_sid ='{$_html['sid']}' 
                                    	   AND 
                                    	        tg_id>'{$_html['id']}'
                                    	 LIMIT
                                    	        1
		");
		      if (!empty($_html['preid']['id'])) {
                $_html['pre'] = '<a class="pre" href="photo_detail.php?id='.$_html['preid']['id'].'#pre">上一页</a>';	 
		      } else { 
                $_html['pre'] = '<span class="pre">到底了</span>';
		      }
		//下一页
		$_html['nexid'] = _fetch_array("SELECT
                          		     max(tg_id)
		          AS
		              id
                          	FROM
                          		     tg_photo
                          	WHERE
                          		     tg_sid='{$_html['sid']}'
                       AND
                          		            tg_id<'{$_html['id']}'
                       LIMIT
                          		            1
		");
		      if (!empty($_html['nexid']['id'])) {
		        $_html['nex'] = '<a class="nex" href="photo_detail.php?id='.$_html['nexid']['id'].'#nex">下一页</a>';
		      } else {
		      	$_html['nex'] = '<span class="nex">到底了</span>';
		      }
	} else {
		_alert_back('img不存在啊');
	}
} else {
	_alert_back('img不存在啊');
}
$_percent = 0.3;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="js/code.js"></script>
<script src="js/article.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
</head>

<body>
<a name="pre"></a><a name="nex"></a> 
<?php 
require './includes/header.inc.php';
?>

<div id="photo">
  <h2><?php echo $_html['name']?></h2>
  <dl class="detail">
    <dd class="name"><?php echo $_html['name']?></dd>
    <dt><?php echo $_html['pre']?><img src="<?php echo $_html['url']?>" /><?php echo $_html['nex']?></dt>
    <dd><a href="photo_show.php?id=<?php echo $_html['sid']?>">[返回]</a></dd>
    <dd>访问量：(<strong><?php echo $_html['readcount']?></strong>) 评论量：(<strong><?php echo $_html['commendcount']?></strong>) 上传者：<?php echo $_html['username']?> 发表图：<?php echo $_html['date']?></dd>
    <dd>简介：<?php echo $_html['content']?></dd>
    <dd></dd>
  </dl>
  
  <?php 
    $yyc = 1;
    while ($_rows = _fetch_array_list($_result)) {
      $_html['usernmae'] = $_rows['tg_username'];
      $_html['content'] = $_rows['tg_content'];
      $_html['date'] = $_rows['tg_date'];
      $_html = _html($_html);
      
      if (!!$_rrows = _fetch_array("SELECT 
                                          tg_id,
                                          tg_sex,
                                          tg_face,
                                          tg_email,
                                          tg_url,
                                          tg_switch,
                                          tg_autograph
                                     FROM
                                          tg_user
                                    WHERE
                                          tg_username='{$_html['username']}'"
      )) {
              $_html['userid'] = $_rrows['tg_id'];
              $_html['sex'] = $_rrows['tg_sex'];
              $_html['face'] = $_rrows['tg_face'];
              $_html['email'] = $_rrows['tg_email'];
              $_html['url'] = $_rrows['tg_url'];
              $_html['switch'] = $_rrows['tg_switch'];
              $_html['autograph'] = $_rrows['tg_autograph'];
              $_html = _html($_html);
      } else {
      	
      }
  ?> 
  <p class="line"></p>
  <div class="re">
      <dl>
        <dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
        <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['face']?>"/></dt>
        <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
        <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
        <dd class="guest">写短信</dd>
        <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
        <dd class="email">邮件：<a href="mail:1092879991@qq.com" target="_blank"><?php echo $_html['email']?></a></dd>
        <dd class="url">网站：<a href="http://www.1092879991.com"><?php echo $_html['url']?></a></dd>
      </dl>
      <div class="content">
        <div class="user">
          <span><?php echo $yyc+(($_page-1) * $_pagesize);?></span><?php ?> | 发表于：<?php echo $_html['date']?>
        </div>
        <h3>主题：<?php echo $_html['name']?> <img src="images/icon1.gif" /> <?php echo @$_html['re']?><!--<span>[<a href="#yc" name="re" title="回复<?php echo $i + (($_page - 1) * $_pagesize)?>楼<?php echo $_html['username']?>">回复</a>]</span> --> </h3>
        <div class="detail">
          <?php echo _ubb($_html['content'])?>
          <?php
          
          //签名
          // if($_html['switch'] == 1){
          //   echo '<p class="autograph">'._ubb($_html['autograph']).'</p>';
          // }
          ?>
        </div>
      </div>
        
      </div>  

    <?php 
      $yyc++;
      }
      _paging(1);
    ?>
  
  

  <?php if(isset($_COOKIE['username'])){?>
  
  <p class="line"></p>
  <form method="post" action="?action=rephoto">
    <input type="hidden" name="sid" value="<?php echo $_html['id']?>"/>
    <dl class="rephoto">
      <dd>标　　题：<input type="text" name="title" class="text" value="re:<?php echo $_html['name']?>"/><em>(*必填，至少两位)</em></dd>
      <dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a>　<a href="javascript:;">Q图系列[2]</a>　<a href="javascript:;">Q图系列[3]</a></dd>   
      <dd>
        <?php include './includes/ubb.inc.php';?>
        <textarea name="content" rows="9"></textarea>
      </dd>
        <dd>
                     验证码　：
        <input type="text" name="code" class="text yzm" /><img src="code.php" id="code" />
        <input type="submit" class="submit" value="发贴"/></dd>
    </dl>
  </form>
  
  <?php }?>
</div>



<?php 
require './includes/footer.inc.php';
?>  
</body>
</html>











