window.onload = function (){
	code();
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function(){
	//验证验证码是否正确
	  if(fm.code.value.length != 4){
		  alert('验证码必须4位');
		  fm.code.value ='';//错误清空
		  fm.code.focus();//错误空将光标移动qq处
		  return false;
	  }
	//内容验证
	  if(fm.content.value.length < 3 ||fm.content.value.length > 200 ){
		  alert('短信内容不的小于3位或大于200位');
		  //不正确就中止
		  fm.content.focus();//错误后将光标移动用户名处
		  return false;
	  }
  };
};	