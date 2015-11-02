function onResponse(response) 
{ 
    /*switch (obj.type)
    {*/
           // case "create":
                    objectsBody.innerHTML += response.Element;

                    $('#addThemeModalWindow').hide();
            //break;

           /* case "edit":
                    var objElement = $("#Theme_"+obj.themeId).find($(".objImg")).get(0)
                    objElement.src = obj.themeIMG
                    var objElement = $("#Theme_"+obj.themeId).find($(".name")).get(0)
                    objElement.textContent = obj.themeName
                    var objElement = $("#Theme_"+obj.themeId).find($(".objDiscription")).get(0)
                    objElement.textContent = obj.themeDiscription				
            break;
    } */ 
}

function NewTheme()
{
	var formData = new FormData($('#newTheme')[0])
    $.ajax({
	  type: "POST",
	  url: "http://textbooks/",
     processData: false,
     contentType: false,
     dataType: 'json',
	  data: formData,
	  success: function(obj){
              alert(obj.Msg);
              if(obj.success)
              {
                objectsBody.innerHTML += obj.Element;
              }

              $('#newTheme').hide();
          }
      });
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
          dataType: 'json',
	  data: "getThemeInfo="+themeId,
	  success: function(obj){
	    
            obj = [
                "Тема: "+obj.Caption,
                "Автор: "+obj.AutorFIO,
                "Уроков: "+obj.LessonsCount,
                "Презентаций: "+obj.PresentationsCount,
                "Описание: "+obj.Discription
            ]
            
	    for (var i = 1; i < themeInfoContent.children.length-1; i++) {	    		    	
	    	themeInfoContent.children[i].innerHTML = obj[i-1];	    		    	
	    };            

		$('#ThemeInfo').show()
	  }
	});
}

function EditTheme()
{
    var formData = new FormData($('#editThemeForm')[0])
    $.ajax({
	  type: "POST",
	  url: "http://textbooks/",
     processData: false,
     contentType: false,
          dataType: 'json',
	  data: formData,
	  success: function(obj){
              alert(obj.Msg);
              if(obj.success)
              {
                $("#Theme_"+obj.themeId).remove();
                objectsBody.innerHTML += obj.Element;
              }

              $('#editTheme').hide();
          }
      });
}

function FillEditTheme (obj, themeId) {
	editThemeInput.value = themeId;

	var objElement = $(obj).parents("div.objBox").find($(".name")).get(0)
	var destinationElement = $('input[name=editThemeName]').get(0)
	destinationElement.value = objElement.textContent

	var objElement = $(obj).parents("div.objBox").find($(".objDiscription")).get(0)
	var destinationElement = $('textarea[name=editThemeDiscription]').get(0)
	destinationElement.value = objElement.textContent
	$('#editTheme').show();
}

/*                    --------Уроки и презентации--------                        */
//Новый урок или презентация
function newLesson (form) 
{
	var formData = new FormData($(form)[0]);

    $.ajax( {
      url: 'http://textbooks/',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (msg) 
      {
      	alert(msg);
      }
    } );
}