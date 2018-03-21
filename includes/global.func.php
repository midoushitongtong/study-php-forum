<?php 
function _remove_dir($dirName)
{
  if(! is_dir($dirName))
  {
    return false;
  }
  $handle = @opendir($dirName);
  while(($file = @readdir($handle)) !== false)
  {
    if($file != '.' && $file != '..')
    {
      $dir = $dirName . '/' . $file;
      is_dir($dir) ? _remove_dir($dir) : @unlink($dir);
    }
  }
  closedir($handle);
  return rmdir($dirName) ;
}

//程序执行时间计算
function returntime(){
  $mtime = explode(' ', microtime());
  return $mtime[1] + $mtime[0];
}
//程序执行时间计算

//限时
function _timed($_now_time,$_pre_time,$_second){
	if($_now_time - $_pre_time < $_second){
	 _alert_back('请休息一会在发啊');
	}
}
//限时
//验证码错误弹
function _alert_back($_no){
  echo "<script type='text/javascript'>alert('".$_no."');history.back();</script>";
  exit();
}
//验证码错误弹

//弹出
function _alert_close($_info) {
	echo "<script type='text/javascript'>alert('$_info');window.close();</script>";
	exit();
}
//弹出



//
function _manage_login(){
	if((!isset($_COOKIE['username'])) || (!isset($_SESSION['admin']))){
		_alert_back('不能这么干啊');
	}
}
//


//注册成功调转函数
function _location($_tishi,$_url){
  //为了某些需求 跳转不需要提示执行
  //如果需要提示,执行下面这句话
  if(!empty($_tishi)){
    echo "<script type='text/javascript'>alert('".$_tishi."');location.href='$_url';</script>";
    exit();
 //如果不需要提示,执行下面这句话
}else{
	header('Location:'.$_url);
  }
}
//注册成功调转函数

//登录状态无法执行操作
function _login_state(){
	if(isset($_COOKIE['username'])){
      _alert_back('登录状态无法进行本操作!');
	}
}
//登录状态无法执行操作

//页面显示
function _page($_sql,$_size) {
	//将所有参数取出来  外部可以访问
	global $_pagesize, $_pagenum, $_pageabsolute, $_page,$_num;
	if (isset($_GET['page'])) {
		$_page = @$_GET['page'];
		if(empty($_page) || $_page <= 0 || !is_numeric($_page)) {
			$_page = 1;
		}
	} else {
	//取整
		$_page = 1;
	}
	$_pagesize = $_size;
	$_pagenum = ($_page - 1) * $_pagesize;
	//首页获取数据总和
	$_num = mysql_num_rows(mysql_query($_sql));
	//页码的变量
	//数据库为0条数据的情况下默认第一页
	if ($_num == 0) {
		$_pageabsolute = 1;
	} else {
		$_pageabsolute = ceil($_num/$_pagesize);
	}
	if($_page > $_pageabsolute){
		$_page = $_pageabsolute;
	}
}
//页面显示

//分页函数
function _paging($_type){
  
  
  global $_page,$_pageabsolute,$_num,$_id;
  
  if($_type == 1){
    echo '<div id="page_num">';
    echo '<ul>';
        for ($i=0;$i<$_pageabsolute;$i++) {
                if($_page == ($i+1)){
                  echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
                }else{
                  echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
                }
        }
    echo '</ul>';
    echo '</div>';
}elseif($_type == 2){
    echo '<div id="page_text">';
    echo '<ul>';
    echo '<li>'.$_page.'/'.$_pageabsolute.' 页 | </li>';
    echo '<li>共有<strong>'.$_num.'</strong> 条数据 </li>';
        if($_page == 1){
        	echo '<li>首页 | </li>';
        	echo '<li>上一页 | </li>';
        }else{
            //别的页面调取这块分页代码可以使用
            //PHP自带的函数 来连接目录SCRIPT = $_SERVER['SCRIPT_NAME'];
            //自定义的方法来连接目录
        	echo '<li><a href="'.SCRIPT.'.php">首页</a> | </li>';
        	echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a> | </li>';
        }
        if($_page == $_pageabsolute){
        	echo '<li>下一页 | </li>';
        	echo '<li>尾页| </li>';
        }else{
        	echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a> | </li>';
        	echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_pageabsolute).'">尾页</a> | </li>';
        }
  echo '</ul>';
  echo '</div>';
  }else{
    _paging(2);
  }
}
//分页函数


//关闭session
// function _session_destroy() {
// 	if (session_start()) {
// 		session_destroy();
// 	}
// }
function _session_destroy(){
	session_destroy();
}
//关闭session


//关闭cookie
function _unsetcookies(){
	setcookie('username','',time()-1);
	setcookie('uniqid','',time()-1);
	_session_destroy();
	_location(null,'index.php');
}
//关闭cookie

//判断唯一标示符 uniqid
function _uniqid($_mysql_uniqid,$_cookie_uniqid){
	if($_mysql_uniqid != $_cookie_uniqid){
		_alert_back('唯一标示符异常');
	}
}
//判断唯一标示符 uniqid

//转义字符
// function _mysql_string($_string){
// 	if(!GPC){
// 	   addslashes($_string);
// 	}
// 	return addslashes($_string);
// }

function _mysql_string($_string) {
	//get_magic_quotes_gpc()如果开启状态，那么就不需要转义
	if (GPC) {
		if (is_array($_string)) {
			foreach ($_string as $_key => $_value) {
				$_string[$_key] = _mysql_string($_value);   //这里采用了递归，如果不理解，那么还是用htmlspecialchars
			}
		} else {
			$_string = addslashes($_string);
		}
	} 
	return $_string;
}

//转义字符

//验证码
function _check_code($_first_code,$_end_code){
  if($_first_code != $_end_code){
    _alert_back('验证码不正确');
  }
}
//包装验证码

//激活标识符
function _sha1_uniqid(){
  return _mysql_string(sha1(uniqid(rand(),true)));
}
//激活标识符

//验证码代码
function code($width = 83,$height = 23,$_rnd_code = 3){
  $_nmsg='';
  for($i=0;$i<$_rnd_code;$i++){
    $_nmsg.=dechex(mt_rand(0,15));
  }
  $_SESSION['code'] = $_nmsg;
  //图像宽和高
  //   $width = 75;
  //   $height = 25;
  //创建图像
  $img = imagecreatetruecolor($width, $height);
  //给图片分配颜色
  $_white = imagecolorallocate($img,255,255,255);
  //创建边框
  $colorBorder=imagecolorallocate($img,0,0,0);
  imagerectangle($img,0,0,$width-1,$height-1,$colorBorder);
  //创建线条
  for($i=0;$i<3;$i++){
    $rand_color = imagecolorallocate($img,mt_rand(50,255),mt_rand(50,255),mt_rand(50,255));
    imageline($img,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$rand_color);
  }
  //创建雪花
  for($i=0;$i<100;$i++){
    $rnd_color = imagecolorallocate($img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
    imagestring($img,1,mt_rand(1,$width),mt_rand(1,$height),'-',$rnd_color);
  }
  //填充
  imagefill($img,0,0,$_white);
  //输出验证码
  for ($i=0;$i<strlen($_SESSION['code']);$i++) {
    $rnd_color = imagecolorallocate($img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
    imagestring($img,5,$i*$width/$_rnd_code+mt_rand(1,10),mt_rand(1,$height/2),$_SESSION['code'][$i],$rnd_color);
  }
  //输出图像
  header('Content-type:image/png');
  imagepng($img);
  //清理图像
  imagedestroy($img);
}
//验证码代码

//thumb
function _thumb($_filename,$_percent) {
  $_n = explode('.',$_filename);
  //png标头文件
  header('Content-type: image/png');
  //获取文件长宽
  list($_width,$_height) = getimagesize($_filename);
  //生成新的长宽
  $_new_width = $_width * $_percent;
  $_new_height = $_height * $_percent;
  
  
  //创建新的突破
  $_new_image = imagecreatetruecolor($_new_width,$_new_height);
  
  //创建新的突破
  switch($_n[1]){
    case 'jpg': $_image = imagecreatefromjpeg($_filename);
      break;
  	case 'png': $_image = imagecreatefrompng($_filename);
      break;
  	case 'gif': $_image = imagecreatefromgif($_filename);
      break;
  }
  //赋值
  imagecopyresampled($_new_image,$_image,0,0,0,0,$_new_width,$_new_height,$_width,$_height);
  
  imagepng($_new_image);
  
	
	
	
	
  imagedestroy($_new_image);
  imagedestroy($_image);
}
//thubm

//截取函数显示所有文字中的1到12
//显示标题
function _title($_string,$_strlen){
	if(mb_strlen($_string,'utf-8') > $_strlen){
      $_string = mb_substr($_string,0,$_strlen,'utf-8').'...';
	}
	return $_string;
}
//显示标题

//将html代码转义 防止破坏界面

function _html($string){
  if(is_array($string)){
  	 foreach ($string as $_key => $_value){
  	 	$string[$_key] = _html($_value);//递归函数 不理解就不用了
  	 }
  }else{
  	$string = htmlspecialchars($string);
  }
  return $string;
}
//将html代码转义 防止破坏界面


//设置xml

function _set_xml($_xmlfile,$_clean){
  //W有写入功能
  $_fp = fopen('new.xml','w');
  if(!$_fp){
    exit('文件不存在');
  }
  //锁定
  flock($_fp,LOCK_EX);
  
  //写数据 输出
  $_string = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "<vip>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<id>{$_clean['id']}</id>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<username>{$_clean['username']}</username>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<sex>{$_clean['sex']}</sex>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<face>{$_clean['face']}</face>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<email>{$_clean['email']}</email>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "\t<url>{$_clean['url']}</url>\r\n";
  fwrite($_fp,$_string,strlen($_string));
  
  //继续写入
  $_string = "</vip>";
  fwrite($_fp,$_string,strlen($_string));
  
  //解锁
  flock($_fp,LOCK_UN);
  
  //关闭fopen
  fclose($_fp);
}




//输出xml的值
function _get_xml($_xmlfile){
//显示最新注册的用户

//判断是否存在文件

//创建空数组存放xml里面的字段
$_html = array();

if(file_exists($_xmlfile)){
  //生成的xml文件 将文件输出成字符串
  $_xml = file_get_contents($_xmlfile);
  //筛选vip里面的值
  preg_match_all('/<vip>(.*)<\/vip>/s',$_xml,$_dom);
  foreach ($_dom[1] as $_value){
    //帅选id里得内容
    preg_match_all('/<id>(.*)<\/id>/s',$_value,$_id);
    // 	  echo $_id[1][0];
    //帅选username里得内容
    preg_match_all('/<username>(.*)<\/username>/s',$_value,$_username);
    // 	  echo $_username[1][0];
    //帅选sex里得内容
    preg_match_all('/<sex>(.*)<\/sex>/s',$_value,$_sex);
    // 	  echo $_sex[1][0];
    //帅选face里得内容
    preg_match_all('/<face>(.*)<\/face>/s',$_value,$_face);
    // 	  echo $_face[1][0];
    //帅选email里得内容
    preg_match_all('/<email>(.*)<\/email>/s',$_value,$_email);
    // 	  echo $_email[1][0]
    //帅选url里得内容
    preg_match_all('/<url>(.*)<\/url>/s',$_value,$_url);
    // 	  echo $_url [1][0]
    
    //赋值
    $_html['id'] = $_id[1][0];
    $_html['username'] = $_username[1][0];
    $_html['sex'] = $_sex[1][0];
    $_html['face'] = $_face[1][0];
    $_html['email'] = $_email[1][0];
    $_html['url'] = $_url[1][0];
  }
}else{
  echo 'xml文件不存在,请检查';
}
return $_html;
}


//ubb
function _ubb($_string){
  $_string = nl2br($_string);
  $_string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$_string);
  $_string = preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$_string);
  $_string = preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$_string);
  $_string = preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$_string);
  $_string = preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$_string);
  $_string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color=:\1">\2</span>',$_string);
  $_string = preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$_string);
  $_string = preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1" >\1</a>',$_string);
  $_string = preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="123"/>',$_string);
  $_string = preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:390px;height:390px;" src="\1"></embed>',$_string);
  return $_string;
}
//ubb






?>