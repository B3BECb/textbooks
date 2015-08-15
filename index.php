<?php
	session_start();
	require_once "libs/loginCore/login.php";
	require_once "libs/mainMenuCore/mainMenu.php"; 
	require_once "libs/ExtentionsClassificator.php";

	@mysql_connect("localhost", "root") or die ("Could not connect to MySQL server!"); 
	@mysql_select_db("textbooks") or die ("Could not select products database!");
	
	/*вход и выход из системы*/
	if (!$_SESSION['userId'])
	{
		$autorization = new AutClass();
		if($_GET['log'])
		{	
			$autorization->logIn($_GET['log'], $_GET['pas']);

			switch ($_SESSION['userType']) {
				case 0:
					die ("Неверно введен логин или пароль");
					break;

				case 1:
					echo "учен";
					break;

				case 2://учитель
					$teacher = new teacherMainMenu($_SESSION['userId']);
					$teacher->getMenu();
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
							default:
								$teacher = new teacherMainMenu($_SESSION['userId']);
								$teacher->getMenu(); return;
							break;
						}			

					foreach ($_POST as $key => $value) //парсинг post запросов
						switch ($key) {		
							case 'newThemeName':
								$teacher->newTheme($_POST['newThemeName'], $_POST['newThemeDiscription'], $_FILES['upload']);
								return;
							break;
						}	
				break;

				case 3:
					echo "адм";
					break;
			}		
	}
?>