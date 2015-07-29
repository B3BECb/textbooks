<?php 
	/**
	* Родительский класс пользователей git!
	*/
	class user
	{		
		private $id;
		private $fio;

		function jsOnResponse($obj)  //ответ сервера
		{  
			echo ' 
			<script type="text/javascript"> 
			window.parent.onResponse("'.$obj.'");
			</script> 
			';  
		}
	}

	/**
	* Класс управления главным меню для учителя
	*/
	class teacherMainMenu extends user
	{
		function __construct($teacherId)
		{
			$this->id = $teacherId;
			//Получаем доп. информацию о учителе
			$this->fio = mysql_result(mysql_query("SELECT fio FROM teachers WHERE teacher_id = $this->id"), 0);
		}

		function getMenu()
		{
			include "teacherMainMenu.html";
		}

		function getFIO()
		{
			return $this->fio;
		}

		function getThemes()
		{
			//выбрать все темы, где владелец этот учитель
			//mysql_query("SELECT * FROM ");

			//сформировать ячейки. удалить/изменить/аннотация. в ячейках выползающий див с названием. задний фон - картинка
		}

		function newTheme($themeName, $themeDiscription = '', $themeIMG = '')
		{
			//заносим новую тему в БД
			mysql_query("INSERT INTO themes values (null, '$themeName', $this->id, '$themeDiscription', '".$themeIMG['name']."')");
			$lastInsertId = mysql_insert_id();

			//Создать новую директорию темы
			mkdir("themes/theme_$lastInsertId");

			if ($themeIMG)
			{
				$dir = "themes/theme_$lastInsertId"; // путь к каталогу загрузок на сервере			
				$name = basename($themeIMG['name']);//имя файла и расширение
				$file = "$dir/$name";//полный путь к файлу
				
				$success = move_uploaded_file($themeIMG['tmp_name'], $file);

			} else $success = 1;						

			if ($success) $this->jsOnResponse("{'message':'Тема создана.', 'success':'" . $success . "', 'teacherId':'" . $this->id . "', 'themeName':'" . $themeName . "', 'themeDiscription':'" . $themeDiscription . "', 'themeIMG':'" . $themeIMG['name'] . "'}");
			else $this->jsOnResponse("{'message':'Тема не создана!', 'success':'" . $success . "'}");
			//если есть файл, то загружаем его
		}
	}
?>