<?php
	/**
	* Родительский класс пользователей
	*/
	class user
	{		
		private $id;
		private $fio;
		private $mysqli;

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
		function __construct($mysqli, $teacherId)
		{
			$this->mysqli = $mysqli;

			$this->id = $teacherId;

			//Получаем доп. информацию о учителе
			$teacherFio = $this->mysqli->query("SELECT fio FROM teachers WHERE teacher_id = $this->id");
			$this->fio = $this->mysqli->result($teacherFio, 0);
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
			$teacherThemes = $this->mysqli->query("SELECT theme_id, themeName, discription, img FROM themes WHERE teacher_id_fk = 1");
			if(!mysqli_num_rows($teacherThemes)) return;

			while($theme = mysqli_fetch_array($teacherThemes))
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
							<span class="topButtonText">Cведения</span>
							<img src="../../svgs/info.svg">
						</div>
					</span>
					<span class="topBtn">
						<div onClick="RemoveTheme(this, '.$theme['theme_id'].');" class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Удалить</span>
							<img src="../../svgs/delete.svg">
						</div>
					</span>
				</div>
			</div>';
			}
		}

		function newTheme($themeName, $themeDiscription = '', $themeIMG = '')
		{			
			try
			{
				if (empty($themeName)) throw new Exception("Недопустимое название темы.");	

				//проверка на валидность картинки			
				if ($themeIMG['tmp_name'])
				{
					$ExtentionsClassificator = new extensionClassificator();
					$extention = pathinfo($themeIMG['name'], PATHINFO_EXTENSION);
					if ($ExtentionsClassificator->classificate($extention) != "pics") throw new Exception("Недопустимое расширение файла($extention)."); 
				}

				//заносим новую тему в БД
				$this->mysqli->query("INSERT INTO themes values (null, '$themeName', $this->id, '$themeDiscription', '".$themeIMG['name']."')");
				$lastInsertId = $this->mysqli->insert_id;

				//Создать новую директорию темы
				mkdir("themes/theme_$lastInsertId");

				if ($themeIMG['tmp_name'])
				{					
					$dir = "themes/theme_$lastInsertId"; // путь к каталогу загрузок на сервере			
					$name = basename($themeIMG['name']);//имя файла и расширение
					$file = "$dir/$name";//полный путь к файлу				

					if (!($success = move_uploaded_file($themeIMG['tmp_name'], $file))) throw new Exception("Ошибка перемещения файла.");

				} else $success = 1;
			}
			catch (Exception $e)
			{
				$this->jsOnResponse("{'message':'Ошибка создания темы! ".$e->getMessage()."', 'success':'0'}");
			}						

			if ($success) $this->jsOnResponse("{'message':'Тема создана.', 'success':'1', 'themeId':'" . $lastInsertId . "', 'themeName':`$themeName`, 'themeDiscription':`$themeDiscription`, 'themeIMG':'$file'}");
		}

		function RemoveTheme($themeId)
		{
			$this->removeDirectory("themes/theme_$themeId");
-			$this->mysqli->query("DELETE FROM themes WHERE theme_id = $themeId;");

			echo "Тема удалена.";
		}
	}
?>