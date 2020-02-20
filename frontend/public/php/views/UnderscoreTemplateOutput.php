<?php 

require DIR_BASE.'/php/factory/UnderScoreTemplateEmbedder.php';
 
class UnderscoreTemplateOutput {
	var $dir_base;	
	var $ute;

	public function __construct($dir_base){
		$this->dir_base = $dir_base;
		$this->ute = new UnderScoreTemplateEmbedder();
	}

	public function getTemplates($id, $dir, $live=false){
 		return $this->ute->get($id, $this->dir_base.'/'.$dir, $live);
 	}

}

?>