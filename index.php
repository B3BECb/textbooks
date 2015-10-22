<?php
	session_start();
	require_once "libs/loginCore/login.php";
	//require_once "libs/mainMenuCore/mainMenu.php"; 
	require_once "libs/mainMenuCore/teacherLogick.php";
	require_once "libs/ExtentionsClassificator.php";
	require_once "libs/ExtendedDefultClasses.php";

	$mysqli = new ExtendedMysqli("localhost", "root", "admin", "textbooks") or die ("Could not connect to MySQL server!"); 
	$mysqli->query("SET NAMES utf8");
	
	/*вход и выход из системы*/
	if (!$_SESSION['userId'])
	{ 
		$autorization = new AutClass();
		if($_POST['log'])
		{	
			$autorization->logIn($_POST['log'], $_POST['pas']);

			switch ($_SESSION['userType']) {
				case 1:
					echo "учен";
					break;

				case 2://учитель
					$teacher = new teacherMainMenu($_SESSION['userId']);
					echo "1";
					break;

				case 3:
					echo "адм";
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
		switch ($_SESSION['userType']) {
				case 0:
					die ("Неверно введен логин или пароль");
					break;

				case 1:
					echo "учен";
					break;

				case 2://учитель
					$teacher = new teacherMainMenu($_SESSION['userId']);

					if (empty($_GET) && empty($_POST)) //если нет запросов
					{						
						$teacher->getMenu();
						return;
					}

					foreach ($_GET as $key => $value) //парсинг get запросов
						switch ($key) {

							case 'getThemeInfo':
								$teacher->GetThemeInfo($_GET['getThemeInfo']);
								return;

							case 'theme':
								$teacher->getLessonsMenu();
								return;

							case 'removeTheme':
								$teacher->RemoveTheme($_GET['removeTheme']); 
								return;

							case 'exit':
								$autorization = new AutClass();
								$autorization->logOut();
								return;	

							case 'sayHello':
								echo "Hi! I'm Get request";
							return;							

							default:
								$teacher->getMenu(); return;
								break;
						}			

					foreach ($_POST as $key => $value) //парсинг post запросов
						switch ($key) {		
							case 'newThemeName':
								$teacher->newTheme($_POST['newThemeName'], $_POST['newThemeDiscription'], $_FILES['upload']);
								return;
							break;

							case 'sayHello':
								echo "Hi! I'm Post request";print_r($_FILES['upload']);
							return;

							case 'editTheme':						
								$teacher->EditTheme($_POST['editTheme'], $_POST['editThemeName'], $_POST['editThemeDiscription'], ($_POST['editThemePict']) ? $_FILES['upload'] : "");
								return;

							default:
								$teacher->getMenu(); return;
							break;
						}	
				break;

				case 3:
					echo "адм";
					break;
			}		
	}
?>