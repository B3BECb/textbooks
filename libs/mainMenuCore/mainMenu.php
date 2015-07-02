<?php 
	/**
	* Родительский класс пользователей git!
	*/
	class user
	{		
		private $id;
		private $fio;
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
			mysql_query("INSERT INTO themes values (null, '$themeName', $this->id, '$themeDiscription', '$themeIMG')");
			//если есть файл, то загружаем его
		}
	}
?>