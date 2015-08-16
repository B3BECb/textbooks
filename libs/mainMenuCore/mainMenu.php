<?php
	/**
	* 
	*/
	class EmptyThemeName extends Exception
	{		
	}

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

		function removeDirectory($dir) 
		{
			
			if ($objs = glob($dir."/*")) 
			{
				foreach($objs as $obj) 
				{
					is_dir($obj) ? removeDirectory($obj) : unlink($obj);
				}
			}
			rmdir($dir);
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
			$teacherThemes = mysql_query("SELECT theme_id, themeName, discription, img FROM themes WHERE teacher_id_fk = 1");
			if(!mysql_num_rows($teacherThemes)) return 0;

			//$themes = mysql_fetch_array($teacherThemes, MYSQLI_ASSOC);
			while($theme = mysql_fetch_array($teacherThemes))
			{
				echo '<div class="objBox">
				<img class="objImg" src='.(($theme['img'])?('themes/theme_'.$theme['theme_id'].'/'.$theme['img']):'').'>
				<div class="objName"> 
					<span class="name">'.$theme['themeName'].'</span>
				</div>	
				<div class="objDiscription">
					'.$theme['discription'].' 
				</div>			
				<div class="objControls">
					<span class="topBtn">
						<div class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Изменить</span>
							<img src="../../svgs/edit.svg">
						</div>
					</span>
					<span class="topBtn">
						<div id="theme_'.$theme['theme_id'].'" class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Удалить</span>
							<img src="../../svgs/delete.svg">
						</div>
					</span>
				</div>
			</div>';
			}
			//сформировать ячейки. удалить/изменить/аннотация. в ячейках выползающий див с названием. задний фон - картинка
		}

		function newTheme($themeName, $themeDiscription = '', $themeIMG = '')
		{			
			try
			{
				if (empty($themeName)) throw new EmptyThemeName("Недопустимое название темы.");
				//заносим новую тему в БД
				mysql_query("INSERT INTO themes values (null, '$themeName', $this->id, '$themeDiscription', '".$themeIMG['name']."')");
				$lastInsertId = mysql_insert_id();

				//Создать новую директорию темы
				mkdir("themes/theme_$lastInsertId");

				if ($themeIMG['tmp_name'])
				{
					$ExtentionsClassificator = new extensionClassificator();
					$extention = pathinfo($themeIMG['name'], PATHINFO_EXTENSION);
					if ($ExtentionsClassificator->classificate($extention) != "pics") throw new Exception("Недопустимое расширение файла($extention)."); 

					$dir = "themes/theme_$lastInsertId"; // путь к каталогу загрузок на сервере			
					$name = basename($themeIMG['name']);//имя файла и расширение
					$file = "$dir/$name";//полный путь к файлу				

					if (!($success = move_uploaded_file($themeIMG['tmp_name'], $file))) throw new Exception("Ошибка перемещения файла.");

				} else $success = 1;
			}
			catch (EmptyThemeName $e)
			{
				$this->jsOnResponse("{'message':'Ошибка создания темы! ".$e->getMessage()."', 'success':'0'}");
			}
			catch (Exception $e)
			{
				$this->removeDirectory("themes/theme_$lastInsertId");
				mysql_query("DELETE FROM themes WHERE theme_id = $lastInsertId;");

				$this->jsOnResponse("{'message':'Ошибка создания темы! ".$e->getMessage()."', 'success':'0'}");
			}						

			if ($success) $this->jsOnResponse("{'message':'Тема создана.', 'success':'1', 'teacherId':'" . $this->id . "', 'themeName':'$themeName', 'themeDiscription':'$themeDiscription', 'themeIMG':'$file'}");
		}
	}
?>