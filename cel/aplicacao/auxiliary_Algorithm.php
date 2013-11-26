<?php
//FILE: auxiliary_Akgorithm.php
//Function: Perfm some actions to help the algoritm.php
//


//Confirm the relation on te list, return $key if the relation exist and -1 if not

function exist_relation($relation, $list){

	foreach($list as $key=>$relation){

		if( @$relation->verbo == $relation ) {

			return $key;

		}
	}

	return -1;
}

//Confirm the concept on te list, return $key if the relation exist and -1 if not
// I can't find this function implement on the code, but I'm not secure to remove it.
function existe_conceito($conc, $list){

	foreach($list as $key=>$conc1){

		if( $conc1->nome == $conc ) {

			return $key;

		}
	}

	return -1;
}

?>