<?php 
if(!defined('IN_TG')){
  exit('禁止调用');
}
?>
<div id="member_sidebar">
  <h2>后台管理中心</h2>
    <dl>
      <dt>管理</dt>
      <dd><a href="manage.php">后台首页管理</a></dd>
      <dd><a href="manage_set.php">后台系统设置</a></dd>
    </dl>
    <dl>
      <dt>会员管理</dt>
      <dd><a href="manage_member.php">会员列表</a></dd>
      <dd><a href="manage_job.php">职务设置</a></dd>
    </dl>
</div>