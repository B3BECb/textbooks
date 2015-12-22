<?php
	require_once "EducationObject.php";
	
	class Lesson extends EducationObject implements JsonSerializable, IEducationalObject
	{                
	    public function jsonSerialize()
	    {
	        
	    }
	
	    public function EducationObjectInfo($id) {
	
	    }
	
	    public function getElement() {
	
	    }
	
	    public function initializeFields($currentTheme, $lessonName, $lessonType, $lessonDiscription = '', $lessonIMG = '') 
	    {
	    	$this->$_name = $lessonName;
	    	$this->$_discription = $lessonDiscription;
	    	$this->$_img = $lessonIMG;
	    	$this->$_theme = $currentTheme;						
	    }
	
	} 
?>