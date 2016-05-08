// Работа с темами для ученика
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