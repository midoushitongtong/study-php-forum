//等在网页加载完毕在执行
window.onload = function (){
	code();
	//登陆验证
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function(){
	//验证js代码开始
	  //用户名的strlen
	  if(fm.username.value.length < 2 || fm.username.value.length > 20){
		  alert('用户名不得小于2位或者大于20位');
		  //不正确就中止
		  fm.username.value = '';//错误清空
		  fm.username.focus();//错误后将光标移动到用户名处
		  return false;
	    }
	  //用户名敏感字符
	  if(/[<>\'\"\ 　]/.test(fm.username.value)){
		  alert('用户名包含非法字符');
		  fm.username.value ='';//错误清空
		  fm.username.focus();//错误后将光标移动到用户名处
		  return false;
	   }
	  //密码
	  if(fm.password.value.length < 6 ){
		  alert('密码不得小于6位');
		  //不正确就中止
		  fm.password.value = '';//错误清空
		  fm.password.focus();//错误后将光标移动用户名处
		  return false;
	  }
	  //验证验证码是否正确
	  if(fm.code.value.length != 4){
		  alert('验证码必须4位');
		  fm.code.value ='';//错误清空
		  fm.code.focus();//错误空将光标移动qq处
		  return false;
	  }
	//验证js代码结束
	};
};












