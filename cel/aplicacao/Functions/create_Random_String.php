<?php
/***************************************
 * File: create_Random_String
 * Purpose: create a random String in order to make new passwords or any random
 * keys, returns the random String.
 */

function create_Randon_String($n){	
    
                assert($n !=NULL);    
		//$String = aracteres Aceitaveis
                
                $string = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz0123456789";
		$cod = "";
		for($a = 0;$a < $n;$a++){
                    
			$rand = rand(0,61);
			$cod .= substr($string,$rand,1);
		}	
		return $cod;
	}

?>
