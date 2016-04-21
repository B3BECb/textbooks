<?php
	require_once('mainMenu.php');
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
			include "studentMainMenu.html";
		}

        // Темы
		function getThemes() {
			global $mysqli;
			// Выбор всех тем, которые ученик может просматривать
			$studentThemes = $mysqli->query("SELECT theme_id, themeName, discription, img FROM themes");
			if(!mysqli_num_rows($studentThemes)) return;
            // Создание и вывод темы
			while($theme = mysqli_fetch_array($studentThemes)) {
                $currentTheme = new Theme();
                $currentTheme->GetThemeConstruct($theme);
                echo $currentTheme->getElement();
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