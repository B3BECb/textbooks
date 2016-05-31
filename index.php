<?php
	session_start();
	require_once "libs/loginCore/login.php";
	//require_once "libs/mainMenuCore/mainMenu.php"; 
    require_once "libs/mainMenuCore/student/studentLogick.php";
	require_once "libs/mainMenuCore/teacher/teacherLogick.php";
    require_once "libs/editors/EditorHandlers.php";
	require_once "libs/ExtentionsClassificator.php";
	require_once "libs/ExtendedDefultClasses.php";

	$mysqli = new ExtendedMysqli("localhost", "root", "", "textbooks") or die ("Could not connect to MySQL server!");
	$mysqli->query("SET NAMES utf8");
	
	/*вход и выход из системы*/
	if (empty($_SESSION['userId']))
    {
		$autorization = new AutClass();
		if(!empty($_POST['log']))
        {
			$autorization->logIn($_POST['log'], $_POST['pas']);
			switch ($_SESSION['userType'])
            {
				case 1:
                    $student = new studentMainMenu($_SESSION['userId']);
                    echo "0";
					break;

				case 2:
					$teacher = new teacherMainMenu($_SESSION['userId']);
					echo "1";
					break;

				case 3:
					echo "2";
                    $admin = new adminMainMenu($_SESSION['userId']);
					break;
			}
		}
        else
        {
			$autorization->getAutForm();
		}
	}
    else
    {
        switch($_SESSION['userType'])
        {
            case 0:
                die ("Неверно введен логин или пароль");
                break;

            case 1: // Ученик
                $student = new studentMainMenu($_SESSION['userId']);
                if(empty($_GET) && empty($_POST))
                {
                    $student->getMenu();
                    return;
                }
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case 'getThemeInfo':
                            $student->GetThemeInfo($_GET['getThemeInfo']);
                            return;

                        case 'getEducationObjectInfo':
                            $student->GetEducationObjectInfo($_GET['getEducationObjectInfo']/*, $_GET['educationObjectType']*/);
                            return;
                            
                        case 'theme':
                            $_SESSION['CurrentTheme'] = $_GET['theme'];
                            $student->getLessonsMenu();
                            return;
                        
                        case 'lesson':
                            require_once("/themes/theme_".$_GET['theme']."/lesson_".$_GET['lesson']."/lesson".$_GET['lesson'].".html");
                            return;
                            
                        case 'exit':
                            $autorization = new AutClass();
                            $autorization->logOut();
                            return;
                        
                        case 'sayHello':
                            echo "Hi! I'm Get request";
                            return;
                        
                        default:
                            $student->getMenu();
                            return;
                            break;
                    }
                }
                foreach($_POST as $key => $value)
                {
                    switch($key)
                    {
                        case 'sayHello':
                            echo "Hi! I'm Post request" . $_POST['newLessonDiscription'];
                            print_r($_FILES['upload']);
                            return;
                        default:
                            $student->getMenu();
                            return;
                    }
                }
                break;

            case 2: // Учитель
                $teacher = new teacherMainMenu($_SESSION['userId']);
                if(empty($_GET) && empty($_POST))
                {
                    $teacher->getMenu();
                    return;
                }
                foreach($_GET as $key => $value)
                {
                    switch($key)
                    {
                        case 'getThemeInfo':
                            $teacher->GetThemeInfo($_GET['getThemeInfo']);
                            return;

                        case 'getEducationObjectInfo':
                            $teacher->GetEducationObjectInfo($_GET['getEducationObjectInfo']/*, $_GET['educationObjectType']*/);
                            return;

                        case 'theme':
                            $_SESSION['CurrentTheme'] = $_GET['theme'];
                            $teacher->getLessonsMenu();
                            return;

                        case 'lesson':
                            $editor = new EditorHandlers();
                            $editor->GetEditor($_GET['lesson']);
                            return;

                        case 'removeTheme':
                            $teacher->RemoveTheme($_GET['removeTheme']);
                            return;

                        case 'removeLesson':
                            $teacher->RemoveLesson($_GET['removeLesson']);
                            return;

                        case 'exit':
                            $autorization = new AutClass();
                            $autorization->logOut();
                            return;

                        case 'sayHello':
                            echo "Hi! I'm Get request";
                            return;

                        default:
                            $teacher->getMenu();
                            return;
                    }
                }
                foreach($_POST as $key => $value)
                {
                    switch($key)
                    {
                        case 'newThemeName':
                            $teacher->newTheme($_POST['newThemeName'], $_POST['newThemeDiscription'], $_FILES['upload']);
                            return;

                        case 'newLessonName':
                            $teacher->newLesson($_POST['newLessonName'], $_POST['lessonType'], $_POST['newLessonDiscription'], $_FILES['upload']);
                            return;

                        case 'sayHello':
                            echo "Hi! I'm Post request" . $_POST['newLessonDiscription'];
                            print_r($_FILES['upload']);
                            return;

                        case 'editTheme':
                            $teacher->EditTheme($_POST['editTheme'], $_POST['editThemeName'], $_POST['editThemeDiscription'], @($_POST['editThemePict']) ? "" : $_FILES['upload']);
                            return;

                        case 'editLesson':
                            $teacher->EditLesson($_POST['editLesson'], $_POST['editThemeName'], $_POST['editThemeDiscription'], @($_POST['editThemePict']) ? "" : $_FILES['upload']);
                            return;

                        case 'test':
                            $editor = new EditorHandlers();
                            $editor->Save($_POST['editabledata']);
                            return;

                        default:
                            $teacher->getMenu();
                            return;
                    }
                }
                break;

            case 3: // Администратор
                echo "Вы авторизированы как администратор";
                break;
        }
    }
?>