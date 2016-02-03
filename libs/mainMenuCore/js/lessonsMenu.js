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

function EditEducationObject()
{
    var formData = new FormData($('#editEducationObjectForm')[0])
    $.ajax({
        type: "POST",
        url: "http://textbooks/",
        processData: false,
        contentType: false,
        //dataType: 'json',
        data: formData,
        success: function(obj)
        {
            alert(obj.Msg);
            if(obj.success)
            {
                $("#Theme_"+obj.themeId).remove();
                objectsBody.innerHTML += obj.Element;
            }

            $('#editEducationObject').hide();
        } });     
}

function FillEditEducationObject (obj, objId) 
{
    editEducationObject.value = objId;
    
    var objElement = $(obj).parents("div.objBox").find($('input[name=objType]')).get(0)
    var destinationElement = $('input[name=EducationObjectType]').get(0)
    destinationElement.value = objElement.value

    var objElement = $(obj).parents("div.objBox").find($(".name")).get(0)
    var destinationElement = $('input[name=editEducationObjectName]').get(0)
    destinationElement.value = objElement.textContent

    var objElement = $(obj).parents("div.objBox").find($(".objDiscription")).get(0)
    var destinationElement = $('textarea[name=editEducationObjectDiscription]').get(0)
    destinationElement.value = objElement.textContent
    $('#editEducationObject').show();
}
