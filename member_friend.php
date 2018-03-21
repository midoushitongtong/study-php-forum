<?php
session_start();
define('IN_TG',true);
define('SCRIPT','member_friend');
require './includes/common.inc.php';

if(!isset($_COOKIE['username'])){
	_location('请先登录','login.php');
}

if(@$_GET['action'] == 'check' && isset($_GET['id'])){
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
		_query("UPDATE tg_friend SET tg_state=1 WHERE tg_id='{$_GET['id']}'");
		if(_affected_rows() == 1){
			_location('验证成功','member_friend.php');
		}else{
			_close();
			_alert_back('验证失败');
		}
	}else{
		_alert_back('非法登陆'); 
	}
}
if(@$_GET['action'] == 'delete' && isset($_POST['ids'])){
  $_clean = array();
  $_clean['ids'] = _mysql_string(implode(',',$_POST['ids']));
  if(!!$_rows = _fetch_array("SELECT
                                    tg_uniqid
                                FROM
                                    tg_user
                               WHERE
                                    tg_username='{$_COOKIE['username']}'
                               LIMIT
                                    1
      ")){
      _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
    
      _query("DELETE FROM
                          tg_friend
                    WHERE
                          tg_id
                       IN
                          ({$_clean['ids']})
                      ");
         
    	  if(_affected_rows() == 1){
            _close();
            _location('删除成功','member_friend.php');
          }else{
            _close();
            _alert_back('删除失败');
          }
      }else{
            _alert_back('非法登录');
      }
  }
global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_friend WHERE tg_touser='{$_COOKIE['username']}' OR tg_fromuser='{$_COOKIE['username']}'",12);
$_result = _query("SELECT 
	                     tg_id,tg_state,tg_touser,tg_fromuser,tg_content,tg_date
                    FROM
                         tg_friend
                   WHERE
                         tg_touser='{$_COOKIE['username']}'
                      OR
                         tg_fromuser='{$_COOKIE['username']}'
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
    <h2>好友管理中心</h2>
    <form method="post" action="?action=delete">
    <table border="0">
      <tr><th>好友</th><th>请求内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
      <?php 
        $_html = array();
        while (!!$_rows = _fetch_array_list($_result)){
        $_html['id'] = $_rows['tg_id'];
        $_html['touser'] = $_rows['tg_touser'];
        $_html['fromuser'] = $_rows['tg_fromuser'];
        $_html['content'] = $_rows['tg_content'];
        $_html['state'] = $_rows['tg_state'];
        $_html['date'] = $_rows['tg_date'];
        $_html = _html($_html);
        
          if($_html['touser'] == $_COOKIE['username']){
          	   $_html['friend'] = $_html['fromuser'];
          	 if(empty($_html['state'])){
          	 	$_html['state_html'] = '<a href="?action=check&id='.$_html['id'].'" style="color:#f60">你未验证</a>';
          	 }else{
          	 	$_html['state_html'] = '<span style="color:green;">通过</span>';
          	 }
          }elseif ($_html['fromuser'] == $_COOKIE['username']){
          	$_html['friend'] = $_html['touser'];
          	if(empty($_html['state'])){
          		$_html['state_html'] = '<span style="color:blue;">对方未验证</span>';
          	}else{
          		$_html['state_html'] = '<span style="color:green;">通过</span>';
          	}
          }
					
      ?>
      <tr><td><?php echo $_html['friend']?></td><td title="<?php echo $_html['content']?>"><?php echo _title($_html['content'],12); ?></td><td><?php echo $_html['date']?></td><td><?php echo $_html['state_html']?></td><td><input name="ids[]" value="<?php echo $_html['id']?>" type="checkbox" /></td></tr>    
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