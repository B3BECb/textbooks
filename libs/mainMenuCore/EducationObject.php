<?php
	class EducationObject
	{
		protected $_name;
		protected $_discription;
		protected $_img;
		protected $_theme;
		 
	    public function NewEducationObject($type)
	    {
	        if(((int)$type) == 0) 
	        {
	            $lesson = new Lesson();
	            return $lesson;
	        }
	        else 
	        {
	            $Presentation = new Presentation();
	            return $Presentation;
	        }
	    }
	}
?>