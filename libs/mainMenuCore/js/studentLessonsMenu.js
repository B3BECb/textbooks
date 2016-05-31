// Работа с уроками и презентациями для ученика
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