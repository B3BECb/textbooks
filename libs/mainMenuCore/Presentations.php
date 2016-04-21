<?php
	require_once "EducationObject.php";
	
	class Presentation extends EducationObject implements JsonSerializable, IEducationalObject
    {
    	private $_slidesCount;
    	
        public function jsonSerialize() 
        {

        }

        public function Info($id) {

        }

        public function getElement() {

        }

        public function initializeFields($slidesCount, $currentTheme, $lessonName, $lessonType, $lessonDiscription = '', $lessonIMG = '') 
        {
            $this->$_name = $lessonName;
        	$this->$_discription = $lessonDiscription;
        	$this->$_img = $lessonIMG;
        	$this->$_theme = $currentTheme;	
        	$this->$_slidesCount = $slidesCount;
        }

    }
?>