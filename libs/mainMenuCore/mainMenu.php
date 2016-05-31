<?php

	require_once "Theme.php";
	require_once "Lessons.php";
	require_once "Presentations.php";
	require_once "EducationObject.php";
	/**
	* Интерфейс пользователя
	*/
	interface IUser
	{
		public function EditTheme($themeId, $themeName, $discription, $img);
        
		public function EditLesson($lessId, $lessName, $discription, $img);        

		public function getMenu();
		
		public function getThemes();
		
		public function getLessonsMenu();
	}
        
    /**
    * Интерфейс предмета темы
    */
    interface IEducationalObject
    {
        public function getElement();
        
        public function EducationObjectInfo($id);
    }

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

            function RemoveTheme($themeId)
            {
                global $mysqli;

                $this->removeDirectory("themes/theme_$themeId");
                $mysqli->query("DELETE FROM themes WHERE theme_id = $themeId;");

                echo "Тема удалена.";
            }
            
            function RemoveLesson($lessonId) 
            {
				global $mysqli;
				$themeId = $_SESSION['CurrentTheme'];

                $this->removeDirectory("themes/theme_$themeId/lesson_$lessonId");
                $mysqli->query("DELETE FROM lessons WHERE lesson_id = $lessonId;");

                echo "Урок удален.";
           	}

            function GetThemeInfo($theme_id)
            {
                    $theme = new Theme;
                    $theme->ThemeInfo($theme_id);
                    echo json_encode($theme);                 
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
                        $theme->NewThemeConstruct($themeName, $this->id, $themeDiscription, $themeIMG);                            
                }
                catch (Exception $e)
                {
                        echo json_encode(array('Msg' => 'Ошибка создания темы! '.$e->getMessage()));                       
                        return;
                }	
                
                echo json_encode(array('Msg' => 'Тема создана', 'Element' => $theme->getElement()));
            }
            
            function newLesson($lessonName, $lessonType, $lessonDiscription = '', $lessonIMG = '')
            {
                try
                {
                	$currentTheme = $_SESSION['CurrentTheme'];
                    $educObjectFabric = new EducationObject();
                    $educObject = $educObjectFabric->NewEducationObject($lessonType); 
                    $educObject->initializeFields($currentTheme, $lessonName, $lessonDiscription, $lessonIMG);
                    $educObject->Create();
                }
                catch (Exception $e)
                {
                    echo json_encode(array('Msg' => 'Ошибка! '.$e->getMessage()));                       
                    return;
                }
                echo json_encode(array('Msg' => 'Готово.', 'Element' => $educObject->getElement()));
            }
	}           
             	
?>