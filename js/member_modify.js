window.onload = function(){
	code();
	//js验证表单
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function(){
		//密码验证
		if(fm.password.value !=''){
			if(fm.password.value.length < 6){
				alert('密码不得小于6位');
				fm.password.value='';//错误后清空表单
				fm.password.focus();//然后将光标移动到 验证错误 的表单
				return false;
			}
		}
		//邮箱验证
		if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)){
			alert('邮件格式不正确');
			fm.email.value='';//错误后清空表单
			fm.email.focus();//然后将光标移动到 验证错误 的表单
			return false;
		}
		//验证QQ
		if (fm.qq.value != '') {
			if (!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)) {
				alert('QQ号码不正确');
				fm.qq.value = ''; //清空
				fm.qq.focus(); //将焦点以至表单字段
				return false;
			}
		}
		//验证url
		if (fm.url.value != '') {
			if (!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)) {
				alert('网址不合法');
				fm.url.value = ''; //清空
				fm.url.focus(); //将焦点以至表单字段
				return false;
			}
		}
		//验证码验证
			if (fm.code.value.length != 4) {
				alert('验证码必须是4位');
				fm.code.value = ''; //清空
				fm.code.focus(); //将焦点以至表单字段
				return false;
			}
			return true;
	};
};