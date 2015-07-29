function onResponse(d) 
{  
	eval('var obj = ' + d + ';');  
	alert(obj.message); 
	if(obj.success) {
		//Запостить блок
	}  
}