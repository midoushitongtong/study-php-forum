<?php
session_start();
//防止includes被恶意调用
define('IN_TG',true);
//css样式表名称
define('SCRIPT','article');
//核心文件引入
require './includes/common.inc.php';




//精华
if (@$_GET['action'] == 'c' && isset($_GET['id']) && isset($_GET['on'])) {
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
      _query("UPDATE tg_article SET tg_nice='{$_GET['on']}' WHERE tg_id='{$_GET['id']}'"); if(_affected_rows() == 1){_close();_location('ok','article.php?id='.$_GET['id']);}else{_close();_alert_back('不行');}      
	} else {
		_alert_back('你不能这么做');
	}
}
//读取回帖
global $_system;
if(@$_GET['action'] == 'rearticle'){
      if(!empty($_system['code'])){
        _check_code($_POST['code'],$_SESSION['code']);
      }
      if(!!$_rows = _fetch_array("SELECT
                                        tg_uniqid,tg_article_time
                                    FROM
                                        tg_user
                                   WHERE
                                        tg_username='{$_COOKIE['username']}'
                                   LIMIT
                                        1
                                  ")){
      	//验证
      	_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
      	
      	//限时
      	@_timed(time(),$_rows['tg_article_time'],$_system['re']);
      	
      	
      	//接收
      	$_clean = array();
      	$_clean['reid'] = $_POST['reid'];
      	$_clean['type'] = $_POST['type'];
      	$_clean['title'] = $_POST['title'];
      	$_clean['content'] = $_POST['content'];
      	$_clean['username'] = $_COOKIE['username'];
  
      	//
      	_query("INSERT INTO tg_article (
      	                               tg_reid,
      	                               tg_username,
      	                               tg_title,
      	                               tg_type,
      	                               tg_content,
      	                               tg_date
      	                             )
      	                       VALUES(
      	                               '{$_clean['reid']}',
      	                               '{$_clean['username']}',
      	                               '{$_clean['title']}',
      	                               '{$_clean['type']}',
      	                               '{$_clean['content']}',
      	                               NOW()
      	                           
      	                            )
      	       ");
      	if(_affected_rows() == 1){
      	    //setcookie('article_time',time());
      	    $_clean['time'] = time();
      	    _query("UPDATE tg_user SET tg_article_time='{$_clean['time']}' WHERE tg_username='{$_COOKIE['username']}'");
      		_query("UPDATE tg_article SET tg_commendcount=tg_commendcount+1 WHERE tg_id='{$_clean['reid']}'");
      		//_session_destroy();
      		_location('回帖','article.php?id='.$_clean['reid']);
      	}else{
      	  _close();
      	  //_session_destroy();
      	  _alert_back('no');
      	}
    }else{
      _alert_back('非法登陆');
    }
}
//读取回帖

//读取数据
if(@$_GET['id']){
	if(!!$_rows = _fetch_array("SELECT 
	                                   tg_id,tg_nice,tg_username,tg_title,tg_type,tg_content,tg_readcount,tg_commendcount,tg_date,tg_last_modify_date
	                              FROM 
	                                   tg_article 
	                             WHERE 
	                                   tg_reid = 0
	                                   AND
	                                   tg_id='{$_GET['id']}'
	                           ")){
	                            //阅读+1
	                            _query("UPDATE tg_article SET tg_readcount=tg_readcount+1 WHERE tg_id='{$_GET['id']}'");
	                           	              
	                            //有数据之后创新
	                            $_html = array();
	                            $_html['nice'] = $_rows['tg_nice'];
	                            $_html['username_subject'] = $_rows['tg_username'];
	                            $_html['reid'] = $_rows['tg_id'];
	                            $_html['title'] = $_rows['tg_title'];
	                            $_html['type'] = $_rows['tg_type'];
	                            $_html['content'] = $_rows['tg_content'];
	                            $_html['readcount'] = $_rows['tg_readcount'];
	                            $_html['commendcount'] = $_rows['tg_commendcount'];
	                            $_html['date'] = $_rows['tg_date'];
	                            $_html['last_modify_date'] = $_rows['tg_last_modify_date'];
	                            
	                            //创建全局变量，实现带参数分页
	                            global $_id;
	                            $_id = 'id='.$_html['reid'].'&';
	                            
	                            //显示吧主
	                            if($_rows = _fetch_array("SELECT 
	                                                             tg_id,tg_username,tg_sex,tg_face,tg_email,tg_url,tg_switch,tg_autograph
	                                                        FROM
	                                                             tg_user 
	                                                       WHERE 
	                                                             tg_username='{$_html['username_subject']}'")){
	                            //提取信息
	                            $_html['userid'] = $_rows['tg_id'];
	                            $_html['sex'] = $_rows['tg_sex'];
	                            $_html['face'] = $_rows['tg_face'];
	                            $_html['email'] = $_rows['tg_email'];
	                            $_html['url'] = $_rows['tg_url'];
	                            $_html['switch'] = $_rows['tg_switch'];
	                            $_html['autograph'] = $_rows['tg_autograph'];
	                            $_html = _html($_html);
	                            
	                            
	                            //修改帖子
	                            if(@$_html['subject_username'] = $_COOKIE['username'] || isset($_SESSION['admin'])){
	                            	$_html['subject_modify'] = '[<a href="article_modify.php?id='.$_html['reid'].'">修改</a>]';
	                            }
	                            
	                            //读取修改时间
	                            if($_html['last_modify_date'] != '0000-00-00 00:00:00'){
	                            	$_html['last_modify_subject'] = '本帖被'.$_html['username_subject'].'于'.$_html['last_modify_date'].'修改过';
	                            }
	                            
	                            //留言
	                            if(@$_COOKIE['username']){
	                              $_html['rel'] = '<span><a href="#yc" name="re" title="回复给一楼的'.$_html['username_subject'].'">留言</a></span>';
	                            }
	                            
	                            //签名
	                            if($_html['switch'] == 1){
	                              $_html['autograph_html'] = '<p class="autograph">'.$_html['autograph'].'</p>';
	                            }
	                            
	                            //读取回帖
	                            global $_pagesize,$_pagenum,$_page;
	                            _page("SELECT tg_id FROM tg_article WHERE tg_reid='{$_html['reid']}'",21); 
	                            $_result = _query("SELECT
	                                                     tg_username,tg_content,tg_type,tg_date,tg_title
	                                                 FROM
	                                                     tg_article
	                                                WHERE
	                                                     tg_reid='{$_html['reid']}'
                                                 ORDER BY
	                                                     tg_date ASC
	                                                LIMIT
	                                                     $_pagenum,$_pagesize
	                                             ");
	                            }else{
	                              
	                            }
	}else{
	  _alert_back('你不能这么做');
	}
}else{
  _alert_back('你不能这么做');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
</head>

<body>

<?php 
require './includes/header.inc.php';
?>
<div id="article">
  <h2>帖子内容</h2>
  <?php 
    if (!empty($_html['nice'])) {
  ?>
  <img src="images/nice.gif" class="c"/>
  <?php 
    }
  ?>
  <?php 
    if ($_html['readcount'] > 333 && $_html['commendcount'] > 23) {
  ?>
  <img src="images/hot.gif" class="h"/>
  <?php 
    }
  ?>
  <?php if($_page == 1){?>
  <div id="subject">
    <dl>
      <dd class="user"><?php echo $_html['username_subject']?>(<?php echo $_html['sex']?>)[楼主]</dd>
      <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username_subject']?>"/></dt>
      <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
      <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
      <dd class="guest">写短信</dd>
      <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
      <dd class="email">邮件：<a href="mail:1092879991@qq.com" target="_blank"><?php echo $_html['email']?></a></dd>
      <dd class="url">网站：<a href="http://www.1092879991.com"><?php echo $_html['url']?></a></dd>
    </dl>
     <div class="content">
        <div class="user">
            <span>
                                              <?php
                                              if (@$_SESSION['admin']) { 
                                                if (empty($_html['nice'])) {?>
                                              [<a href="article.php?action=c&on=1&id=<?php echo $_html['reid']?>">精华</a>]
                                              <?php } else {?>
                                              [<a href="article.php?action=c&on=0&id=<?php echo $_html['reid']?>">取消</a>]
                                              <?php } }?>
                                              <!--  123 -->
                                              <?php echo @$_html['subject_modify']?> 1# 
            </span>
           <?php echo $_html['username_subject']?> | 发表于：<?php echo $_html['date']?>
         </div>
         <h3>主题：<?php echo $_html['title']?> <img src="images/icon<?php echo $_html['type']?>.gif" /> <?php echo @$_html['rel']?> </h3>
         <div class="detail">
                             <?php echo _ubb($_html['content'])?>
                               <?php echo @$_html['autograph_html']?>
         </div>
         <div class="read">
         <p><?php echo @$_html['last_modify_subject']?></p>
          阅读量:(<?php echo $_html['readcount']?>)评论量:(<?php echo $_html['commendcount']?>)
         </div>
     </div>
  </div>
  <?php }?>
  
  
  <p class="line"></p>
      <?php 
        $yyc = 2;
        while(!!$_rows = _fetch_array_list($_result)){
        	$_html['username'] = $_rows['tg_username'];
        	$_html['type'] = $_rows['tg_type'];
        	$_html['retitle'] = $_rows['tg_title'];
        	$_html['content'] = $_rows['tg_content'];
        	$_html['date'] = $_rows['tg_date'];
            $_html = _html($_html);

            if($_rows = _fetch_array("SELECT
                                            tg_id,tg_username,tg_sex,tg_face,tg_email,tg_url,tg_switch,tg_autograph
                                            FROM
                                            tg_user
                                            WHERE
                                            tg_username='{$_html['username']}'")){
                //提取信息
                $_html['userid'] = $_rows['tg_id'];
                $_html['sex'] = $_rows['tg_sex'];
                $_html['face'] = $_rows['tg_face'];
                $_html['email'] = $_rows['tg_email'];
                $_html['url'] = $_rows['tg_url'];
                $_html['switch'] = $_rows['tg_switch'];
                $_html['autograph'] = $_rows['tg_autograph'];
                $_html = _html($_html);
                
                if($_page == 1 && $yyc == 2){
                  if($_html['username'] == $_html['username_subject']){

                  $_html['username_html'] = $_html['username'].'(楼主)';
                }else{
                  $_html['username_html'] = $_html['username'].'(沙发)';
                }
                }else{
                	$_html['username_html'] =$_html['username'];
                }
                 
             }else{
               //用户       
             }   
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             

             
             //回复
             if(@$_COOKIE['username']){
             	$_html['re'] = ' <span>[<a href="#yc" name="re" title="回复'.( $yyc + (($_page - 1) * $_pagesize)).'楼'. $_html['username'].'">回复</a>]</span> ';
             }
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             //if(@$_COOKIE['username']){
             //	$_html['re'] = '<span><a href="#yc" name="re" title="回复给一楼的'.$_html['username_subject'].'">留言</a></span>';
             //}
      ?>
      <div class="re">
        <dl>
          <dd class="user"><?php echo $_html['username_html']?>(<?php echo $_html['sex']?>)</dd>
          <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username_subject']?>"/></dt>
          <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
          <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']?>">加好友</a></dd>
          <dd class="guest">写短信</dd>
          <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
          <dd class="email">邮件：<a href="mail:1092879991@qq.com" target="_blank"><?php echo $_html['email']?></a></dd>
          <dd class="url">网站：<a href="http://www.1092879991.com"><?php echo $_html['url']?></a></dd>
        </dl>
         <div class="content">
             <div class="user">
               <span><?php echo $yyc+(($_page-1) * $_pagesize);?></span><?php echo $_html['username']?> | 发表于：<?php echo $_html['date']?>
             </div>
             <h3>主题：<?php echo $_html['retitle']?> <img src="images/icon<?php echo $_html['type']?>.gif" /> <?php echo @$_html['re']?><!--<span>[<a href="#yc" name="re" title="回复<?php echo $yyc + (($_page - 1) * $_pagesize)?>楼<?php echo $_html['username']?>">回复</a>]</span> --> </h3>
             <div class="detail">
              <?php echo _ubb($_html['content'])?>
              <?php

              //签名
              if($_html['switch'] == 1){
                echo '<p class="autograph">'._ubb($_html['autograph']).'</p>';
              }
              ?>
             </div>
         </div>
      </div>     
  <p class="line"></p>
  <?php 
      $yyc ++;
    }
    _free_result($_result);
    _paging(1);
  ?>
  
  <?php if(isset($_COOKIE['username'])){?>
  
    <form method="post" action="?action=rearticle">
      <input type="hidden" name="reid" value="<?php echo $_html['reid']?>"/>
      <input type="hidden" name="type" value="<?php echo $_html['type']?>"/>
      <dl>
        <dd>标　　题：<input type="text" name="title" class="text" value="re:<?php echo $_html['title']?>"/><em>(*必填，至少两位)</em></dd>
        <dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a>　<a href="javascript:;">Q图系列[2]</a>　<a href="javascript:;">Q图系列[3]</a></dd>   
        <dd>
          <?php include './includes/ubb.inc.php'; ?>
          <textarea name="content" rows="9"></textarea>
        </dd>
        <dd>
          <?php if(!empty($_system['code'])){?>
                       验证码　：
          <input type="text" name="code" class="text yzm" /><img src="code.php" id="code" />
          <?php }?>
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
