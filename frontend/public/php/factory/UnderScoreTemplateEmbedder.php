<?php 

 
class UnderScoreTemplateEmbedder{

	var $prefix;
	
	public function __construct($opt = null){
		
 	}

 	public function get($id, $url, $live=false){
 		$r = '';
 		$ext = pathinfo($url, PATHINFO_EXTENSION);
 		$fileName = basename($url, ".".$ext);
 		$content = file_get_contents($url);

 		if(!$live){
 	 		$content = $this->strip_single('link', $content);
 		} 

 		$r .= '<script type="text/template" id="'.$id.'">';
 		$r .= $content;
 		$r .= '</script>';

 		return $r;

 	}

 	private function strip_single($tag, $string){
 		$string = preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
 		$string = preg_replace('/<\/'.$tag.'>/i', '', $string);
 		return $string;
 	}

 	private function minify($content){
 		return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$content));
 	}

}


 ?>