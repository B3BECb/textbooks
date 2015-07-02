//дожидаемся полной загрузки страницы
$(function()
{
$(".searchElements").hover
(
	function(){
		$('#searchBtn').css({"width":"90px", "background":"rgba(255, 152, 88, 0.87)", "border-color":"rgb(205, 134, 82)", "border-radius": "23px"})},
	function(){$('#searchBtn').css({"width":"", "background":"", "border-color":"", "border-radius": ""})}
);
});