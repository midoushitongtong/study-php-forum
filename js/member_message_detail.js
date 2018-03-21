window.onload = function(){
	var ret = document.getElementById('return');
	var del = document.getElementById('delete');
	ret.onclick = function(){
		history.back();
	};
	
	//删除
	del.onclick = function(){
		//询问框 确认删除
		if(confirm('是否确认删除短信?')){
								//传个ID 在数据库里将它删除
			location.href='?action=delete&id='+this.name;
		}	
	};
};
