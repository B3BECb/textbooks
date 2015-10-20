<?php

/**
* Базовый класс
*/
class user
{
	public $id;
	public $FIO;
	
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
		
		function getFIO()
		{
			return $this->fio;
		}
		
		abstract function getMenu();
		
		abstract function getThemes();
		
		abstract function newTheme($themeName, $themeDiscription = '', $themeIMG = '');
		
		abstract function RemoveTheme($themeId);
		
		abstract function EditTheme($themeId, $themeName, $discription, $img );
		
		abstract function getLessonsMenu();
}

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
	
?>