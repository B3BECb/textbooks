/*                    --------Уроки и презентации--------                        */
//Новый урок или презентация
function newLesson () 
{
    var formData = new FormData($('#newLesson')[0]);

    $.ajax({
        url: 'http://textbooks/',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
    	dataType: 'json',
        success: function (obj) 
        {
            alert(obj.Msg);
            if(obj.Element)
            {
                objectsBody.innerHTML += obj.Element;
            }

                $('#addLessonModalWindow').hide();
        }
    });
}
    
function RemoveLesson(obj, lessonId)
{
    $.ajax({
        type: "GET",
        url: "http://textbooks/",
        data: "removeLesson="+lessonId,
        success: function(msg)
        {
            alert( msg );
            $(obj).parents("div.objBox").remove();
        }
    });
}

function LessonInfo(themeId)
{
    $.ajax({
      type: "GET",
      url: "http://textbooks/",
      dataType: 'json',
      data: "getEducationObjectInfo="+themeId+"&educationObjectType=1",
      success: function(obj)
      {
      
       obj = [
          		'Название: ' +  obj.Caption,
	            'Тип: ' + (obj.Type)? 'Урок':'Презентация',
	            'Дата создания: ' + obj.Datemade,
	            'Дата последней модификации: ' + obj.LastModification, 
	            'Файлов: ' + obj.FileCount,
	            'Описание: ' + obj.Discription        	
	         ]
        
        	for (var i = 1; i < EducationObjectInfoContent.children.length-1; i++) 
        	{	    		    	
            	EducationObjectInfoContent.children[i].innerHTML = obj[i-1];	
        	}  
            $('#EducationObject').show() 	
     }
    });
}

