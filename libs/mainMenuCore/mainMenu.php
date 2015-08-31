<?php

	/**
	* Класс представления информации о теме
	*/
	class Theme 
	{	
		private $autorId;
		public $AutorFIO;	
		public $Caption;
		public $Discription;
		public $LessonsCount;
		public $PresentationsCount;

		function __construct($id)
		{
			$mysqli = $GLOBALS['mysqli'];

			$autor = $mysqli->query("SELECT teacher_id, fio FROM teachers WHERE teacher_id = (SELECT teacher_id_fk FROM themes WHERE theme_id = $id)");
			$this->AutorId = $mysqli->result($autor, 0,"teacher_id");
			$this->AutorFIO = $mysqli->result($autor, 0,"fio");

			$theme = $mysqli->query("SELECT themeName, discription FROM themes WHERE theme_id = $id");
			$this->Caption = $mysqli->result($theme, 0, "themeName");
			$discription = $mysqli->result($theme, 0, "discription");
			$this->Discription = ( empty($discription ) ) ? "нет" : $discription;

			//$additionalThemeinfo = $mysqli->query();
			$this->LessonsCount = 0/*$mysqli->result($additionalThemeinfo, 0)*/;
			$this->PresentationsCount = 0/*$mysqli->result($additionalThemeinfo, 0)*/;
		}
	}

	/**
	* Родительский класс пользователей
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

		function GetThemeInfo($theme_id)
		{
			$theme = new Theme($theme_id);
			echo "{'2':'<b>Автор:</b> ".$theme->AutorFIO."','1':`<b>Название темы:</b> ".$theme->Caption."`,'5':`<b>Описание:</b> ".$theme->Discription."`,'3':'<b>Количество учебников:</b> ".$theme->LessonsCount."','4':'<b>Количество презентаций:</b> ".$theme->PresentationsCount."'}";
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
			$mysqli = $GLOBALS['mysqli'];

			//Получаем доп. информацию о учителе
			$teacherInfo = $mysqli->query("SELECT fio FROM teachers WHERE teacher_id = $this->id");
			$this->fio = $mysqli->result($teacherInfo, 0);
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
			global $mysqli;

			//выбрать все темы, где владелец этот учитель
			$teacherThemes = $mysqli->query("SELECT theme_id, themeName, discription, img FROM themes WHERE teacher_id_fk = ".$this->id);
			if(!mysqli_num_rows($teacherThemes)) return;

			while($theme = mysqli_fetch_array($teacherThemes))
			{
				echo '<div class="objBox" id="Theme_'.$theme['theme_id'].'">
				<img class="objImg" src='.(($theme['img'])?('themes/theme_'.$theme['theme_id'].'/'.$theme['img']):'').'>
				<div class="objName" onClick="location.href=`?theme='.$theme['theme_id'].'`"> 
					<span class="name">'.$theme['themeName'].'</span>
				</div>	
				<div class="objDiscription" onClick="location.href=`?theme='.$theme['theme_id'].'`">'.$theme['discription'].'</div>			
				<div class="objControls">
					<span class="topBtn">
						<div onClick="EditTheme(this, '.$theme['theme_id'].');" class="controlButton" style="top:0px; position:relative;">
							<span class="topButtonText">Изменить</span>
							<img src="../../svgs/edit.svg">
						</div>
					</span>
					<span class="topBtn">
						<div onClick="ThemeInfo('.$theme['theme_id'].');" class="controlButton" style="top:0px; position:relative;">
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

				global $mysqli;

				//заносим новую тему в БД
				$mysqli->query("INSERT INTO themes values (null, '$themeName', $this->id, '$themeDiscription', '".$themeIMG['name']."')");
				$lastInsertId = $mysqli->insert_id;

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

			if ($success) $this->jsOnResponse("{'type':'create', 'message':'Тема создана.', 'success':'1', 'themeId':'" . $lastInsertId . "', 'themeName':`$themeName`, 'themeDiscription':`$themeDiscription`, 'themeIMG':'$file'}");
		}

		function RemoveTheme($themeId)
		{
			global $mysqli;

			$this->removeDirectory("themes/theme_$themeId");
-			$mysqli->query("DELETE FROM themes WHERE theme_id = $themeId;");

			echo "Тема удалена.";
		}

		function EditTheme($themeId, $themeName, $discription, $img )
		{
			//заменить параметры темы с id

			try 
			{
				if (empty($themeName)) throw new Exception("Недопустимое название темы.");
			
				global $mysqli;

				$name = $mysqli->result($mysqli->query("SELECT img FROM themes WHERE theme_id = $themeId"), 0);
				$dir = "themes/theme_$themeId"; // путь к каталогу загрузок на сервере
				$file = ($name) ? "$dir/$name" : "";//полный путь к файлу

				if ($img['tmp_name'])
				{
					$ExtentionsClassificator = new extensionClassificator();
					$extention = pathinfo($img['name'], PATHINFO_EXTENSION);
					if ($ExtentionsClassificator->classificate($extention) != "pics") throw new Exception("Недопустимое расширение файла($extention)."); 					
			
					$oldName = $name;
					$name = basename($img['name']);//имя файла и расширение
					$file = "$dir/$name";//полный путь к файлу				

					if (!($success = move_uploaded_file($img['tmp_name'], $file))) throw new Exception("Ошибка перемещения файла.");

					//замена = закачать -> удалить 	
					@unlink("themes/theme_$themeId/$oldName");

					$mysqli->query("UPDATE themes SET themeName = '$themeName', discription = '$discription', img = '".$img['name']."' WHERE theme_id = $themeId");

				}
				else
				if (empty($img))
				{
					//картинка не заменяется. запрос на апдейт
					$mysqli->query("UPDATE themes SET themeName = '$themeName', discription = '$discription' WHERE theme_id = $themeId");
				}
				else
				{
					//полное удаление изображения
					$mysqli->query("UPDATE themes SET themeName = '$themeName', discription = '$discription', img = '' WHERE theme_id = $themeId");					
					@unlink("themes/theme_$themeId/$name");
				}

				$this->jsOnResponse("{'type':'edit', 'message':'Тема изменена.', 'success':'1', 'themeId':'$themeId', 'themeName':`$themeName`, 'themeDiscription':`$discription`, 'themeIMG':'$file'}");
			
			} 
			catch (Exception $e) 
			{
				$this->jsOnResponse("{'message':'Ошибка изменения темы! ".$e->getMessage()."', 'success':'0'}");	
			}
			//Заменить все формы на аякс
			//https://youtu.be/qo7Hqwypwcc?list=PLtjuvkyFrt5Wjd-973N117XS7xuuoD6XM&t=1743			
		}

		/*                    --------Уроки и презентации--------                        */

		function getLessonsMenu()
		{
			include "teacherLessonsMenu.html";
		} 

	}
?>