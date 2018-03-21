































































<?php 
define('IN_TG',true);
require './includes/common.inc.php';
$_skinurl = $_SERVER["HTTP_REFERER"];
if (empty($_skinurl) || !isset($_GET['id'])) {
	_alert_back('你不能这么做');
} else {
    setcookie('skin',$_GET['id']);
    //header('Location:'.$_skinurl);
    _location(null,$_skinurl);
}
?>