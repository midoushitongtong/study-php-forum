<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','member_message_detail');

require './includes/common.inc.php';

if(!isset($_COOKIE['username'])){
	_alert_back('清先登陆');
}                                  
if(@$_GET['action'] == 'delete' && isset($_GET['id'])){
    //验证短信存在
    if(!!$_rows = _fetch_array("SELECT
	                             tg_id
                            FROM
                                 tg_message
                           WHERE
                                 tg_id='{$_GET['id']}'   
                           LIMIT
                                 1
                          ")){
        //敏感操作 验证
        if(!!$_rows2 = _fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        	//比对唯一标示符
            _uniqid($_rows2['tg_uniqid'],$_COOKIE['uniqid']);
            //验证成功  删除单条短信
            _query("DELETE FROM 
                                tg_message 
                          WHERE 
                                tg_id='{$_GET['id']}' 
                          LIMIT 
                                1
                   ");
            //删除成功
            
            if(_affected_rows() == 1){
              _close();
              _location('短信删除成功 ','member_message.php');
            }else{
              _close();
              _alert_back('短信删除失败');
            }
        }else{
          _alert_back('非法登陆');
        }
    }else{
    	_alert_back('此短信不存在');
    }
}
//短信删除模块

//判断短信id是否存在
if(isset($_GET['id'])){
	$_rows = _fetch_array("SELECT
    	                         tg_id,tg_state,tg_fromuser,tg_content,tg_date
    	                   FROM
    	                         tg_message
    	                  WHERE
    	                         tg_id='{$_GET['id']}'
    	                  LIMIT
    	                         1	    
    	                    ");
	if($_rows){
	    //如果id存在将state设置为1(已读状态)
	  //修改state状态
	  if(empty($_rows['tg_state'])){
	    _query("UPDATE
	                   tg_message
      	           SET
      	               tg_state='1'
      	         WHERE
      	               tg_id='{$_GET['id']}'
      	         LIMIT
                       1
      	    ");
	         if(!_affected_rows()){
	         	_alert_back('不行');
	         }
	    }
	    //修改state状态 
		$_html = array();
		$_html['id'] = $_rows['tg_id'];
		$_html['fromuser'] = $_rows['tg_fromuser'];
		$_html['content'] = $_rows['tg_content'];
		$_html['date'] = $_rows['tg_date'];
	}else{
		_alert_back('此短信不存在');
	}
}else{
	_alert_back('非法登陆');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/member_message_detail.js"></script>
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
    require './includes /member.inc.php';
  ?>
  <div id="member_main">
    <h2>短信详情界面</h2>
    <dl>
      <dd>发 信 人 ：<?php echo $_html['fromuser']?></dd>
      <dd>内　　容：<?php echo $_html['content']?></dd>
      <dd>发信时间：<?php echo $_html['date']?></dd>
      <dd class="button"><a href="member_message.php"><input type="button" value="返回列表" id="return"/></a><input type="button" name="<?php echo $_html['id']?>" value="删除短信" id="delete" /></dd>
    </dl>
  </div>
</div>
<?php 
require './includes/footer.inc.php';
?>
</body>

</html>











