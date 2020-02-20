<?php

class Utils 
{

	public static function object2array($array) 
	{
		$arrays = array();
		foreach($array as $key => $value) 
		{
			if(is_object($value)) 
			{
				$arrays[$key] = Utils::object2array($value);
			}
			else
			{
				$arrays[$key] = $value;
			}
		}
		
		return $arrays;
	}

}
