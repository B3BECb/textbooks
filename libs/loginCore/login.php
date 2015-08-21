<?php
	/**
	* Класс управления авторизацией
	*/
	class AutClass 
	{
		private $userId;
		private $userType;

		function __construct($userId = 0, $userType = 0)
		{
			$this->userId = $userId;
			$this->userType = $userType;
		}

		function getAutForm()
		{
			include "loginForm.html";
		}

		function logIn($log, $pas)
		{			
			global $mysqli;
			//поиск в 3х таблицах логина и пароля
			$chkResult = $mysqli->query("SELECT * FROM students WHERE  log = '$log' and pas = '$pas'");
			if (!mysqli_num_rows($chkResult))
			{
				$chkResult = $mysqli->query("SELECT * FROM teachers WHERE  log = '$log' and pas = '$pas'");
				if (!mysqli_num_rows($chkResult))
				{
					$chkResult = $mysqli->query("SELECT * FROM administrators WHERE  log = '$log' and pas = '$pas'");
					if (!mysqli_num_rows($chkResult)) 
					{
						$_SESSION['userId'] = 0;
						$_SESSION['userType'] = 0;
						return 0;
					}
					else
					{
						$this->userId = $mysqli->result($chkResult, 0, "admin_id");
						$this->userType = 3;
					}
				}
				else
				{
					$this->userId = $mysqli->result($chkResult, 0, "teacher_id");
					$this->userType = 2;
				}
			}
			else
			{
				$this->userId = $mysqli->result($chkResult, 0, "student_id");
				$this->userType = 1;
			}
						
			$_SESSION['userId'] = $this->userId;
			$_SESSION['userType'] = $this->userType;

			//возвращаем тип пользователя			
			//return 1;
		}

		function logOut()
		{
			unset($_SESSION['userId']);
			unset($_SESSION['userType']);
			
			header('location: index.php');
		}
	}
?>