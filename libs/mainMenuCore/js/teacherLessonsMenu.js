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

function ThemeInfo(lessonId)
{
    $.ajax({
        type: "GET",
        url: "http://textbooks/",
        dataType: 'json',
        data: "getEducationObjectInfo="+lessonId,
        success: function(obj){

            obj = [
                "Заголовок: "+obj.Caption,
                "Файлов: "+obj.FileCount,
                "Дата создания: "+obj.Datemade,
                "Дата изменения: "+obj.LastModification,
                "Описание: "+obj.Discription
            ]

            for (var i = 1; i < themeInfoContent.children.length-1; i++) {
                themeInfoContent.children[i].innerHTML = obj[i-1];
            };

            $('#ThemeInfo').show()
        }
    });
}

function EditLesson()
{
    var formData = new FormData($('#editThemeForm')[0])
    $.ajax({
        type: "POST",
        url: "http://textbooks/",
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formData,
        success: function(obj)
        {
            alert(obj.Msg);
            if(obj.success) {
                $("#Theme_"+obj.lessId).remove();
                objectsBody.innerHTML += obj.Element;
            }

            $('#editTheme').hide();
        } 
    });
}

function FillEditTheme (obj, themeId)
{
    editThemeInput.value = themeId;
    
    var objElement = $(obj).parents("div.objBox").find($(".name")).get(0)
    var destinationElement = $('input[name=editThemeName]').get(0)
    destinationElement.value = objElement.textContent
    
    var objElement = $(obj).parents("div.objBox").find($(".objDiscription")).get(0)
    var destinationElement = $('textarea[name=editThemeDiscription]').get(0)
    destinationElement.value = objElement.textContent
    $('#editTheme').show();
}