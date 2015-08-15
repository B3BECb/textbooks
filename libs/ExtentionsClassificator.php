<?php
/**
* Классификатор форматов
*/
class extensionClassificator 
{
	var $audio;
	var $video;
	var $table;
	var $docs;
	var $pics;
	var $present;
	var $pdf;

	function __construct()
	{
		# инициализация форматов
		$this->audio = array('mp3');
		$this->video = array('mp4','webm');
		$this->table = array('xls','xlsx');
		$this->docs = array('doc','docx');
		$this->pics = array('jpeg','png','svg','jpg','JPEG','PNG','SVG','JPG');
		$this->present = array('ppt','pptx');
		$this->pdf = array('pdf');
	}

	function classificate($extension)
	{
		if (in_array($extension, $this->audio)) return "audio";
		if (in_array($extension, $this->video)) return "video";
		if (in_array($extension, $this->table)) return "table";
		if (in_array($extension, $this->docs)) return "docs";
		if (in_array($extension, $this->pics)) return "pics";
		if (in_array($extension, $this->present)) return "present";
		if (in_array($extension, $this->pdf)) return "pdf";
	}
}
?>