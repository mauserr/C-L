<?php

include_once "CELConfig/CELConfig.inc" ;

function bd_connect()
{
    $ipValor    = CELConfig_ReadVar("BD_ip") ;
	$BDuser     = CELConfig_ReadVar("BD_user") ;
	$BDpassword = CELConfig_ReadVar("BD_password") ;
	$BDdatabase = CELConfig_ReadVar("BD_database") ;

	$connect = mysql_pconnect( $ipValor , "root" , "" ) or die( "Erro na conectar ao BD : " . mysql_error() ) ;
	
	if( $connect && mysql_select_db($BDdatabase) )
		return($connect);
        else
		return(FALSE);
}

?>
