<?php

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
                
	    //нет полиморфизма - используем говно и палки!
	    function NewThemeConstruct($themeName, $userId, $themeDiscription = '', $themeIMG = '')
	    {
	        $this->Caption	= $themeName;
	        $this->Discription	= $themeDiscription;
	        $this->autorId      = $userId;
	        $this->themeIMG	= $themeIMG;
	        
	        $this->newTheme();
	    }
	    
	    function EditThemeConstruct($themeName, $themeId, $themeDiscription = '', $themeIMG = '')
	    {
	        $this->Caption	= $themeName;
	        $this->Discription	= $themeDiscription;
	        $this->themeId      = $themeId;
	        $this->themeIMG	= $themeIMG;
	    }
	    
	    function GetThemeConstruct($result)
	    {
	        $this->Caption	= $result['themeName'];
	        $this->Discription	= $result['discription'];
	        $this->themeId      = $result['theme_id'];
	        $this->themeIMG	= $result['img'];
	    }
                        
		private function newTheme()
		{
			if (empty($this->Caption)) throw new Exception("Недопустимое название темы.");	

			//проверка на валидность картинки			
			if ($this->themeIMG['tmp_name'])
			{
				$ExtentionsClassificator = new extensionClassificator();
				$extention = pathinfo($this->themeIMG['name'], PATHINFO_EXTENSION);
				if ($ExtentionsClassificator->classificate($extention) != "pics") throw new Exception("Недопустимое расширение файла($extention)."); 
			}

			global $mysqli;

			//заносим новую тему в БД
			$mysqli->query("INSERT INTO themes values (null, '$this->Caption', $this->autorId, '$this->Discription', '".$this->themeIMG['name']."')");
			$lastInsertId = $mysqli->insert_id;

			//Создать новую директорию темы
			mkdir("themes/theme_$lastInsertId");
                        
                        $name = '';
                        
			if ($this->themeIMG['tmp_name'])
			{					
				$dir = "themes/theme_$lastInsertId"; // путь к каталогу загрузок на сервере			
				$name = basename($this->themeIMG['name']);//имя файла и расширение
				$file = "$dir/$name";//полный путь к файлу				

				if (!($success = move_uploaded_file($this->themeIMG['tmp_name'], $file))) throw new Exception("Ошибка перемещения файла.");
			}	
                        
                        $this->themeIMG = $name;
                        $this->themeId = $lastInsertId;
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
                
	    public function getElement()
	    {                  
	       return include 'templates/themeElement.htm';
	    }
	
	    public function jsonSerialize() 
	    {//выводить тему
	        return array(
	            'Caption' => $this->Caption,
	            'Discription' => $this->Discription,
	            'themeId' => $this->themeId,
	            'themeIMG' => $this->themeIMG,
	            'AutorFIO' => $this->AutorFIO,
	            'LessonsCount' => $this->LessonsCount,
	            'PresentationsCount' => $this->PresentationsCount
	            );
	    }
}
?>
