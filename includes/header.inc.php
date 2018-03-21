<?php
//防止includes被恶意调用
if(!defined('IN_TG')){
	exit('Access Defined!');
}
?>
<script type="text/javascript" src="js/skin.js"></script>
<div id="header">
  <h1><a href="index.php">多用户留言板系统</a></h1>
  <ul>
    <li><a href="index.php">首页</a></li>
    <?php
      //如果 查看'uername'cookie的信息存在 就输出个人信息
      if(isset($_COOKIE['username'])){
      	echo '<li><a href="member.php">'.$_COOKIE['username'].'·个人中心</a>'.$GLOBALS['message'].'</li>';
      //否则默认界面
      }else{
      	echo '<li><a href="register.php">注册</a></li>';
        echo '<li><a href="login.php"> 登录</a></li>';
      }
    ?>
    <li><a href="blog.php">博友</a></li>
    <li><a href="photo.php">相册</a></li>
    <li class="n" onmouseover='inn()' onmouseout='outn()'>
      <a href="javascript:;">风格</a>
      <dl id="n">
        <dd><a href="skin.php?id=1">一号皮肤点我</a></dd>
        <dd><a href="skin.php?id=2">二号皮肤点我</a></dd>
        <dd><a href="skin.php?id=3">三号皮肤点我</a></dd>
      </dl>
    </li>
    <?php
      if(isset($_COOKIE['username']) && isset($_SESSION['admin'])){
      	echo '<li class="manage"><a href="manage.php" class="manage">管理 </a></li>';
      }
      if(isset($_COOKIE['username'])){
      	echo '<li><a href="logout.php">退出</a></li>';
      }
    ?>
  </ul>
</div>










