function onResponse(d) 
{  
	eval('var obj = ' + d + ';');  
	alert(obj.message); 
	if(obj.success != "0") 
	{
		switch (obj.type)
		{
			case "create":
				objectsBody.innerHTML += 
					`<div class="objBox">
						<img class="objImg" src=`+obj.themeIMG+`>
						<div class="objName"> 
							<span class="name">`+obj.themeName+`</span>
						</div>	
						<div class="objDiscription">`+obj.themeDiscription+`</div>			
						<div class="objControls">
							<span class="topBtn">
								<div onClick="EditTheme(this, `+obj.themeId+`);" class="controlButton" style="top:0px; position:relative;">
									<span class="topButtonText">Изменить</span>
									<img src="../../svgs/edit.svg">
								</div>
							</span>					
							<span class="topBtn">
								<div  onClick="ThemeInfo(`+obj.themeId+`);" class="controlButton" style="top:0px; position:relative;">
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
				$('#addThemeModalWindow').hide();
			break;

			case "edit":
				alert(13213);
			break;
		}
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

function ThemeInfo(themeId)
{
	$.ajax({
	  type: "GET",
	  url: "http://textbooks/",
	  data: "getThemeInfo="+themeId,
	  success: function(msg){
	  	eval('var obj = ' + msg + ';');
	    
	    for (var i = 1; i < themeInfoContent.children.length-1; i++) {	    		    	
	    	themeInfoContent.children[i].innerHTML = obj[i];	    		    	
	    };

		$('#ThemeInfo').show()
	  }
	});
}

function EditTheme (obj, themeId) {
	editThemeInput.value = themeId;

	var objElement = $(obj).parents("div.objBox").find($(".name")).get(0)
	var destinationElement = $('input[name=editThemeName]').get(0)
	destinationElement.value = objElement.textContent

	var objElement = $(obj).parents("div.objBox").find($(".objDiscription")).get(0)
	var destinationElement = $('textarea[name=editThemeDiscription]').get(0)
	destinationElement.value = objElement.textContent
	$('#editTheme').show();
}