<?php
	require_once('/../mainMenu.php');
	// Класс для работы с меню для ученика
	class studentMainMenu extends user implements IUser {
		function __construct($studentId) {
			$this->id = $studentId;
			$mysqli = $GLOBALS['mysqli'];

			//Получаем доп. информацию о ученике
			$studentInfo = $mysqli->query("SELECT fio FROM students WHERE student_id = $this->id");
			$this->fio = $mysqli->result($studentInfo, 0);
		}

		function getMenu() {
			include "studentMainMenu.htm";
		}

        // Темы
		function getThemes() {
			global $mysqli;
			// Выбор всех тем, которые ученик может просматривать			
            $lessons = $mysqli->query("SELECT lessons.theme_id_fk FROM lessonsConstraints, lessons WHERE lessonsConstraints.lesson_id_fk = lessons.lesson_id AND dateStart < '".date("Y.m.d")."' < dateEnd AND student_id_fk = ".$this->id);
            if (!mysqli_num_rows($lessons)) return;
            else {
                $theme_id = mysqli_fetch_array($lessons);
                $studentThemes = $mysqli->query("SELECT theme_id, themeName, discription, img FROM themes WHERE theme_id = ".$theme_id["theme_id_fk"]);
                if(!mysqli_num_rows($studentThemes)) return;
                // Создание и вывод темы
                while($theme = mysqli_fetch_array($studentThemes)) {
                    $currentTheme = new Theme();
                    $currentTheme->GetThemeConstruct($theme);
                    echo $currentTheme->getElement();
                }
            }
            
        }
		function EditTheme($themeId, $themeName, $discription, $img) { }

        // Уроки и презентации
		function getLessonsMenu() {
			include "studentLessonsMenu.htm";
		} 
		
		function getLessons() {
			global $mysqli;
			// Выбор всех уроков, которые ученик может просматривать
			//$themeLessons = $mysqli->query("SELECT lesson_id, lessonName, discription, img FROM lessons WHERE theme_id_fk = ".$_SESSION['CurrentTheme']);
			$themeLessons = $mysqli->query("SELECT lesson_id, lessonName, discription, img FROM lessons WHERE theme_id_fk = ".$_SESSION['CurrentTheme']);
			if(!mysqli_num_rows($themeLessons)) return;
            // Создание и вывод урока
			while($lesson = mysqli_fetch_array($themeLessons)) {
                $currentLesson = new Lesson();
                $currentLesson->GetLessonConstruct($lesson);
                echo $currentLesson->getElement();
			}
		}
	}
?>