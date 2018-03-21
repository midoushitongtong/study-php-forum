<?php
session_start();
//
define('IN_TG',true);
define('SCRIPT','member_message');
require './includes/common.inc.php';
if(!isset($_COOKIE['username'])){
	_location('请先登录','login.php');
}
//判断删除信息
if(@$_GET['action'] == 'delete' && isset($_POST['ids'])){
	$_clean = array();
	//将提交的数据转化成数组
	$_clean['ids'] = _mysql_string(implode(',',$_POST['ids']));
	//防止COOKIES 伪造
    if(!!$_rows = _fetch_array("SELECT
	                              tg_uniqid
	                         FROM
	                              tg_user
	                        WHERE
	                              tg_username='{$_COOKIE['username']}'
                            LIMIT
                                  1
	                      ")){
    	  //查询后验证
    	  _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
    	  
    	  //执行删除
    	  _query("DELETE FROM 
    	                      tg_message
  	                    WHERE
    	                      tg_id
  	                       IN
                              ({$_clean['ids']})
    	      ");
    	  
    	  //成功删除后 跳转 
    	  if(_affected_rows()){
    	  	  _close();
    	  	  _location('删除成功','member_message.php');
    	  }else{
    	  	  _close();
              _alert_back('删除失败');
    	  } 
    }else{
    	_alert_back('非法登录');
    }   
	exit();
}
//分页模块
global $_pagesize,$_pagenum;
//获取总数据  每次显示多少条
_page("SELECT tg_id FROM tg_message WHERE tg_touser='{$_COOKIE['username']}'",15);
$_result = _query("SELECT 
	                     tg_id,tg_state,tg_fromuser,tg_content,tg_date
                    FROM
                         tg_message
                   WHERE
                         tg_touser='{$_COOKIE['username']}'
                ORDER BY
                         tg_date DESC
                   LIMIT
                         $_pagenum,$_pagesize   
                 ");
//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require './includes/title.inc.php';
?>
<script type="text/javascript" src="js/member_message.js"></script>
</head>
<body>
<?php 
require './includes/header.inc.php';
?>

<div id="member">
  <?php 
  require './includes/member.inc.php';
  ?>
  <div id="member_main">
    <h2>会员管理中心</h2>
    <form method="post" action="?action=delete">
    <table border="0">
      <tr><th>发信人</th><th>短信内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
      <?php
        $_html = array();
        while (!!$_rows = _fetch_array_list($_result)){   
        $_html['id'] = $_rows['tg_id'];
        $_html['fromuser'] = $_rows['tg_fromuser'];
        $_html['content'] = $_rows['tg_content'];
        $_html['date'] = $_rows['tg_date'];
        $_html = _html($_html);
        
        //判断 空的为未读
        if(empty($_rows['tg_state'])){
          $_html['state']='<img src="images/read.gif" alt="未读" title="未读" />';
          $_html['content'] = '<strong>'._title($_html['content'],13).'</strong>';
        }else{
          $_html['state']='<img src="images/noread.gif" alt="已读" title="已读" />';
          $_html['content'] = _title($_html['content'],13);
        }
        //判断 空的为未读
        
      ?>
      <tr><td><?php echo $_html['fromuser']?></td><td><a href="member_message_detail.php?id=<?php echo $_html['id']?>" title="<?php echo $_html['content']?>"><?php echo  $_html['content'] ?></a></td><td><?php echo $_html['date']?></td><td><?php echo $_html['state']?></td><td><input name="ids[]" value="<?php echo $_html['id']?>" type="checkbox" /></td></tr>    
      <?php 
        }
        _free_result($_result);
      ?>
      <tr><td colspan="9"><label for="all">全选<input type="checkbox" name="chkall" id="all" /></label><input  type="submit" value="批删除" /></td></tr>
    </table>
    </form>
    <?php 
      _paging(1);
    ?>
  </div>
</div>

<?php 
require './includes/footer.inc.php';
?>
</body>













</html>