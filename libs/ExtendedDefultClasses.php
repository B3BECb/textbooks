<?php
	/**
	* Расширение стандартного класса mysqli
	*/
	class ExtendedMysqli extends mysqli
	{
		function result($res, $row, $field=0) 
		{ 
		    $res->data_seek($row); 
		    $datarow = $res->fetch_array(); 
		    return $datarow[$field]; 
		}
	}
?>