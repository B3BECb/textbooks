<?php

	/**
	* Интерфейс пользователя
	*/
	interface IUser
	{
		public function EditTheme($themeId, $themeName, $discription, $img );

		public function getMenu();
		
		public function getThemes();
		
		public function getLessonsMenu();
	}

	/**
	* Базовый класс
	*/
	class user
	{
		public $id;
		public $FIO;
		
		function jsOnResponse($obj)  //ответ сервера
		{  
			echo ' 
			<script type="text/javascript"> 
			window.parent.onResponse('.$obj.');
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
		
		function RemoveTheme($themeId)
		{
			global $mysqli;

			$this->removeDirectory("themes/theme_$themeId");
			$mysqli->query("DELETE FROM themes WHERE theme_id = $themeId;");

			echo "Тема удалена.";
		}

		function GetThemeInfo($theme_id)
		{
			$theme = new Theme($theme_id);
			echo "{'2':'<b>Автор:</b> ".$theme->AutorFIO."','1':`<b>Название темы:</b> ".$theme->Caption."`,'5':`<b>Описание:</b> ".$theme->Discription."`,'3':'<b>Количество учебников:</b> ".$theme->LessonsCount."','4':'<b>Количество презентаций:</b> ".$theme->PresentationsCount."'}";
		}
		
		function getFIO()
		{
			return $this->fio;
		}
		
		function newTheme($themeName, $themeDiscription = '', $themeIMG = '')
		{			
			try
			{
				$theme = new Theme;
				$theme->newTheme($this->id, $themeName, $themeDiscription = '', $themeIMG = '');
			}
			catch (Exception $e)
			{
				$this->jsOnResponse("{'message':'Ошибка создания темы! ".$e->getMessage()."', 'success':'0'}");
			}						

			//$this->jsOnResponse("{'type':'create', 'message':'Тема создjjана.', 'success':'1', 'themeId':'" . $theme . "', 'themeName':`$theme`, 'themeDiscription':`$theme`, 'themeIMG':'$theme'}");
			$this->jsOnResponse(json_encode($theme));
		}
	}

	/**
	* Класс темы
	*/
	class Theme implements JsonSerializable
	{	
		private $autorId;
		public $AutorFIO;	
		public $Caption;
		public $Discription;
		public $LessonsCount;
		public $PresentationsCount;
		public $themeId;
		public $themeIMG;
		
		function newTheme($userId, $themeName, $themeDiscription = '', $themeIMG = '')
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
			$mysqli->query("INSERT INTO themes values (null, '$themeName', $userId, '$themeDiscription', '".$themeIMG['name']."')");
			$lastInsertId = $mysqli->insert_id;

			//Создать новую директорию темы
			mkdir("themes/theme_$lastInsertId");

			if ($themeIMG['tmp_name'])
			{					
				$dir = "themes/theme_$lastInsertId"; // путь к каталогу загрузок на сервере			
				$name = basename($themeIMG['name']);//имя файла и расширение
				$file = "$dir/$name";//полный путь к файлу				

				if (!($success = move_uploaded_file($themeIMG['tmp_name'], $file))) throw new Exception("Ошибка перемещения файла.");
			}			
				
			$this->Caption		= $themeName;
			$this->Discription	= $themeDiscription;
			$this->themeId		= $lastInsertId;
			$this->themeIMG		= $file;
			 		
		}
		
		function ThemeInfo($id)
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

                public function jsonSerialize() {
                    return array('Caption' => $this->Caption,
                                'Discription' => $this->Discription,
                                'themeId' => $this->themeId,
                                'themeIMG' => $this->themeIMG);
                }

            }
	
?>