<?php

	function autoload_class_multiple_directory($class_name) 
	{

		# List all the class directories in the array.
		$array_paths = array(
			'/beans/', 
			'/dao/',
			'/inc/',
			'/inc/TemplatePower/',
			'/inc/adLDAP/',
			'/inc/PHPMailer/'
		);

		foreach($array_paths as $path)
		{
			$file = DIRNAME(__FILE__).$path.'class.'.$class_name.'.php';
			if(is_file($file)) 
			{
				require_once $file;
			} 

		}
	}

	spl_autoload_register('autoload_class_multiple_directory');