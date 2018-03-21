window.onload = function () {
	var up = document.getElementById('up');
	up.onclick = function (){
		centerWindow('upimg.php?dir='+this.title,'up','388','233');
	};
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function () {
		if (fm.name.value.length < 2 || fm.name.value.length > 23) {
			alert('名称不得小于2位或大于23位');
			fm.name.focus();
			return false;
		}
		if (fm.url.value == '') {
			alert('写url吧');
			fm.url.focus();
			return false;
		}
	};
};
function centerWindow(url,name,width,height){
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}