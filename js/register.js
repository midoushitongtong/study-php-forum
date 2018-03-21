//等在网页加载完毕在执行

window.onload = function (){
	code();
	var faceimg = document.getElementById('faceimg');
	if(faceimg != null){
		faceimg.onclick = function () {
			window.open('face.php','face','width=400,height=400,top=0,left=0,scrollbars=1');
		}
	}
	//表单验证用js减少服务器资源  避免填错 表单清空
	var fm = document.getElementsByTagName('form')[0];
	if(fm == undefined) return;
	fm.onsubmit = function(){
	  //能用客户端验证的，尽量用客户端
	  //js可以选学，
	  //用户名验证
	  if(fm.username.value.length < 2 || fm.username.value.length > 20){
		  alert('用户名不得小于2位或者大于20位');
		  //不正确就中止
		  fm.username.value = '';//错误清空
		  fm.username.focus();//错误后将光标移动到用户名处
		  return false;
	  }
	  //敏感字符
	  if(/[<>\'\"\ \　]/.test(fm.username.value)){
		  alert('用户名包含非法字符');
		  fm.username.value ='';//错误清空
		  fm.username.focus();//错误后将光标移动到用户名处
		  return false;
	  }
	  //用户名验证 
	  
	  //验证密码
	  if(fm.password.value.length < 6 ){
		  alert('密码不得小于6位');
		  //不正确就中止
		  fm.password.value = '';//错误清空
		  fm.password.focus();//错误后将光标移动用户名处
		  return false;
	  }
	  //验证密码
	  //验证密码是否一致
	  if(fm.password.value != fm.notpassword.value){
		  alert('密码和密码确认必须一致');
		  //不正确就中止
		  fm.notpassword.value = '';//错误清空
		  fm.notpassword.focus();//错误后将光标移动用户名处
		  return false;
	  }	
	  //验证密码是否一致
	  
	  //密码提示
	  if(fm.question.value.length < 2 || fm.question.value.length > 20){
		  alert('密码提示不得小于2位或大于20位');
		  //不正确就中止
		  fm.question.value = '';//错误清空
		  fm.question.focus();//错误后将光标移动密码提示处
		  return false;
	  }
      //密码提示
	  
	  //密码回答
	  if(fm.answer.value.length < 2 || fm.answer.value.length > 20){
		  alert('密码回答不得小于2位或大于20位');
		  //不正确就中止
		  fm.answer.value = '';//错误清空
		  fm.answer.focus();//错误后将光标移动密码回答名处
		  return false;
	  }
	  //密码回答
	  
	  //密码回答不得相同
	  if(fm.answer.value == fm.question.value){
		  alert('密码提示与密码回答不得相等');
		  //不正确就中止
		  fm.answer.value = '';//错误清空
		  fm.answer.focus();//错误后将光标移动密码回答名处
		  return false;
	  }  
	  //密码回答不得相同
	  
	  //邮箱验证
	  if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)){
		  alert('邮件格式不正确');
		  fm.email.value ='';//错误清空
		  fm.email.focus();//错误后将光标移动邮件填写处
		  return false;
	  }
	  //邮箱验证
	  
	  //验证QQ
	  if(fm.qq.value !=''){
		  if(!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)){
			  alert('QQ号码不存在');
			  fm.qq.value ='';//错误清空
			  fm.qq.focus();//错误空将光标移动qq处
			  return false;
		  }
	  }
	  //验证QQ

	  //验证url
	  if(fm.url.value !='http://'){
		  if(fm.url.value !=''){
			  if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)){
				  alert('网址不合法');
				  fm.url.value ='';//错误清空
				  fm.url.focus();//错误空将光标移动qq处
				  return false;
			  }
		  }
	  }
	  //验证url	
	  
	  //验证验证码是否正确
	  if(fm.code.value.length != 4){
		  alert('验证码必须4位');
		  fm.code.value ='';//错误清空
		  fm.code.focus();//错误空将光标移动qq处
		  return false;
	  }
	  //验证验证码是否正确
	  
	  //验证用户名正确就继续执行
	  return true;
	}
};












