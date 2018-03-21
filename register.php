<?php
//开启seession功能验证 提交的验证码是否一致
session_start();
//定义常量授权includes下的 公共文件
define('IN_TG',true);
//引入css文件文字
define('SCRIPT','register');
require './includes/common.inc.php';
require './includes/title.inc.php';
//登录状态无法进行操作
_login_state();
global $_system;
//判断是否提交
  if(@$_GET['action']=='register'){
    if(empty($_system['register'])){
    	exit('不要这样注册啊');
    }
    //为了防止恶意注册,跨站攻击
    _check_code($_POST['code'],$_SESSION['code']);
    //引入验证文件
    include 'includes/check.func.php';
    //创建一个空数组，
    $_clean = array();
    //可以通过唯一标识符,每台电脑都会产生不同的。防止恶意注册,伪装表单跨站攻击等
    //第二个用处,就是cookies验证
    $_clean['uniqid'] = _check_uniqid($_POST['uniqid'],$_POST['uniqid']);
    //激活用户,也是唯一标示符,用来刚注册的用户进行激活处理
    $_clean['active'] = _sha1_uniqid();
    //激活用户
    //过滤不正确的用户名赋值给username
    $_clean['username'] = _check_username($_POST['username'],2,16);
    ////过滤不正确的密码赋值给password
    $_clean['password'] = _check_password($_POST['password'],$_POST['notpassword'],6);
    //过滤密码提示密码赋值给question
    $_clean['question'] = _check_question($_POST['question'],3,20);
    //验证密码回答提示密码赋值给answer
    $_clean['answer'] = _check_answer($_POST['question'],$_POST['answer'],1,20);
    //获取性别
    $_clean['sex'] = _check_sex($_POST['sex']);
    //头像
    $_clean['face'] = _check_face($_POST['face']);
    //过滤不正确的邮箱 密码赋值给email
    $_clean['email'] = _check_email($_POST['email'],6,40);
    //过滤不正确的qq密码赋值给qq
    $_clean['qq'] = _check_qq($_POST['qq']);
    //过滤不正确网址密码赋值给url
    $_clean['url'] = _check_url($_POST['url'],40);
      //新增之前，判断用户名是否重复
      $query = mysql_query("SELECT tg_username FROM tg_user WHERE tg_username='{$_clean['username']}' LIMIT 1");
      if(mysql_fetch_array($query)){
        _alert_back('对不起此,用户已被注册!');
      }
    ////新增之前，判断用户名是否重复 调用函数 看不懂先不这么写 先用上面的三句
//     _is_repeat(
//         "SELECT tg_username FROM tg_user WHERE tg_username='{$_clean['username']}'",
//         '对不起此用户已被注册 '
//     );
    ////新增之前，判断用户名是否重复 调用函数 看不懂先不这么写 先用上面的一句
    
    //新增用户//双引号里直接放变量是可以的,比如$_username,如果是数组，就必须加上{},比如{$_clean['username']}
    mysql_query(
               "INSERT INTO tg_user (
                                                  tg_uniqid, 
                                                  tg_active, 
                                                  tg_username,
                                                  tg_password, 
                                                  tg_question, 
                                                  tg_answer, 
                                                  tg_email, 
                                                  tg_qq, 
                                                  tg_url, 
                                                  tg_sex, 
                                                  tg_face, 
                                                  tg_reg_time, 
                                                  tg_last_time, 
                                                  tg_last_ip
                                                 )
                                           VALUES(
                                         '{$_clean['uniqid']}', 
                                         '{$_clean['active']}', 
                                         '{$_clean['username']}', 
                                         '{$_clean['password']}', 
                                         '{$_clean['question']}', 
                                         '{$_clean['answer']}', 
                                         '{$_clean['email']}', 
                                         '{$_clean['qq']}', 
                                         '{$_clean['url']}', 
                                         '{$_clean['sex']}', 
                                         '{$_clean['face']}', 
                                         NOW(),
                                         NOW(),                                   
                                         '{$_SERVER["REMOTE_ADDR"]}'
                                                  )"
    ) or die('SQL语句错误,请检查'.mysql_error());
    //查看最后上线ip
    //'{$_SERVER["REMOTE_ADDR"]}'
    
    //邮件激活
    if(_affected_rows() == 1 ){
      
      //关闭之前 生成用户id 生成xml id 
      $_clean['id'] = _insert_id();
      
      //关闭数据库
      _close();
      //关闭数据库
      
      //注册成功生成xhtml   在主页显示新近用户
      _set_xml('new.xml',$_clean);
      //注册成功生成xhtml   在主页显示新近用户
      
      //注册成功跳转
      _location('邮件已发送,请查收并激活','active.php?active='.$_clean['active']);
      //注册成功跳转
    }else{
      //关闭数据库
      _close();
      //关闭数据库
      //_session_destroy();
      //注册成功跳转
      _location('你注册失败','register.php');
      //注册成功跳转
    }
     //邮件激活
    
    //直接返回注册成功
//     _close();
//     //关闭数据库
    
//     //注册成功跳转
//     _location('恭喜你注册成功','index.php');
//     //注册成功跳转
    //直接返回注册成功
    
}else{
    //唯一标识符,每台电脑都会产生不同的
    $_SESSION['uniqid'] = $uniqid = _sha1_uniqid();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/code.js" ></script>
<script type="text/javascript" src="js/register.js" ></script>
</head>
<body>

<?php 
  require './includes/header.inc.php';
?>

<div id="register">
  <h2>注册用户</h2>
  <?php if(!empty($_system['register'])){?>
  <form method="post" name ="register" action="register.php?action=register">
  <input type="hidden" name="uniqid" value="<?php echo $uniqid ?>" />
    <dl>
      <dt>请认真填写以下内容</dt>
      <dd>用户名　：<input type="text" name="username" class="text" /><em>(*必填，至少两位)</em></dd>
      <dd>密　　码：<input type="password" name="password" class="text" /><em>(*必填，至少六位)</em></dd>
      <dd>确认密码：<input type="password" name="notpassword" class="text" /><em>(*必填，同上)</em></dd>
      <dd>密码提示：<input type="text" name="question" class="text" /><em>(*必填，至少两位)</em></dd>
      <dd>密码回答：<input type="text" name="answer" class="text" /><em>(*必填，至少两位)</em></dd>
      <dd>性　　别：<input type="radio" name="sex" value="男" checked="checked"/>男 <input type="radio" name="sex" value="女"/>女</dd>
      <dd class="face"> <input type="hidden" name="face" value="face/m01.gif" /><img src="face/m01.gif" alt="头像选择" id="faceimg"/></dd>      
      <dd>电子邮件：<input type="text" name="email" class="text" /><em>(*必填，用于激活账户)</em></dd>
	  <dd>　Q Q　：<input type="text" name="qq" class="text" /></dd>
      <dd>主页地址：<input type="text" name="url" class="text" value="http://" /></dd>
	  <dd>验证码　：<input type="text" name="code" class="text yzm" /><img src="code.php" id="code" /></dd>
      <dd><input type="submit" class="submit" value="注册"/></dd>
    </dl>
  </form>
  <?php }else{
  	echo '<h3 style="text-align:center">本站关闭注册</h3>';
  }?>
</div>

<?php
  require './includes/footer.inc.php';  
?>
</body>
</html>












