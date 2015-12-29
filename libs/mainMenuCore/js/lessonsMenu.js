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
    
function RemoveTheme(obj, lessonId)
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


