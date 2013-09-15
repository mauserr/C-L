<?php
/*************************************************************
 * File: showSource.php
 * purpose: Call a function that show the project source code
 * 
 ************************************************************/

$file = $HTTP_GET_VARS['file'];

if(isset($HTTP_GET_VARS["file"])){
    
    show_source($file);
    echo "<br><input type='button' value='Voltar' onclick='javascript:history.back();'/>";

}

?>
