<?php
//防止includes被恶意调用
if(!defined('IN_TG')){
	exit ('Access Defined!');
}
//关闭
_close();
?>
<div id="footer">
  <p>本程序执行耗时为<?php echo round((returntime() - START_TIME),3)?>秒</p>
  <p>版权信息<span>@yyc</span></p>
  <p>本程序代码可以任意更改<span>@yyc</span></p>
</div>