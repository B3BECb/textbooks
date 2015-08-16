function onResponse(d) 
{  
	eval('var obj = ' + d + ';');  
	alert(obj.message); 
	if(obj.success != "0") {
		objectsBody.innerHTML += 
			`<div class="objBox">
				<img class="objImg" src=`+obj.themeIMG+`>
				<div class="objName"> 
					<span class="name">`+obj.themeName+`</span>
				</div>	
				<div class="objDiscription">
					`+obj.themeDiscription+` 
				</div>			
				<div class="objControls">
					<span class="topBtn">
						<div class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Изменить</span>
							<img src="../../svgs/edit.svg">
						</div>
					</span>					
					<span class="topBtn">
						<div class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Cведения</span>
							<img src="../../svgs/info.svg">
						</div>
					</span>
					<span class="topBtn">
						<div  onClick="RemoveTheme(this, `+obj.themeId+`);" class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Удалить</span>
							<img src="../../svgs/delete.svg">
						</div>
					</span>
				</div>
			</div>`
	}  
}

function RemoveTheme(obj, themeId)
{
	$.ajax({
	  type: "GET",
	  url: "http://textbooks/",
	  data: "removeTheme="+themeId,
	  success: function(msg){
	    alert( msg );
	    $(obj).parents("div.objBox").remove();
	  }
	});
}

