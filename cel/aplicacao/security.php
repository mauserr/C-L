<?php
/*************************************************************
 * File: security.php
 * purpose: Removes the metacharacters of the PHP
 * About the data_prepare function
 * 
 ************************************************************/

function escape_metacharacter ( $string )
{
     assert(is_string($string));
     
	 $string = preg_replace("/[][{}()*+?.\\^$|]/i", "def", $string);
	 
     return $string;   
}

function data_prepare( $string ) 
{	
	assert(is_string($string));
	
	$string  = preg_replace("/&/i", "/&amp;/", $string);
	    
	// Removes the html an php tags from the string	
	$string = strip_tags($string);
        
	// Verify if the directive get_magic_quotes_gpc() is activated, if it is, the function striplashes is used in the string
	$string = get_magic_quotes_gpc() ? stripslashes($string) : $string;
	$string = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($string) : mysql_escape_string($string);
	
    return $string;
}

?>
