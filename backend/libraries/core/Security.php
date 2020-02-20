<?php

class Security 
{

	public static function cleanParameters($parameters=array()) 
	{
		$cleanParameters = array();
		foreach($parameters as $key => $value) 
		{
			if(is_array($value)) 
			{
				$cleanParameters[$key] = Security::cleanParameters($value);
			}
			else
			{
				$cleanParameters[$key] = htmlentities($value);
				$cleanParameters[$key] = stripslashes($value);
			}
		}
		
		return $cleanParameters;
	}

}
