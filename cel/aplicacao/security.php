<?php

// Removes the metacharacters of the PHP
function escape_metacharacter ( $string )
{
    
	 $string = preg_replace("/[][{}()*+?.\\^$|]/i", "def", $string);
	 
     return $string;   
}

function data_prepare( $string ) 
{
	//Removes the empty spaces in the beginning and ending of the string

	// Replaces the & by amp; (to avoid troubles generating the XML)	
	$string  = preg_replace("/&/i", "/&amp;/", $string);
	
        
	// Removes the html an php tags from the string	
	$string = strip_tags($string);
        
	
	// Verify if the directive get_magic_quotes_gpc() is activated, if it is, the function striplashes is used in the string
	$string = get_magic_quotes_gpc() ? stripslashes($string) : $string;
	$string = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($string) : mysql_escape_string($string);
	
        return $string;
}

?>
