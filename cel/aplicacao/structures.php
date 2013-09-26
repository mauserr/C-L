<?php
/*************************************************************
 * File: structures.php
 * purpose: define structures for lexic concepts.
 * 
 ************************************************************/
$_SESSION["estruturas"] = 1;

class conceito
{
	var $name = null;
	var $descricao = null;
	var $relations = null;
	var $subConception = null;
	var $namespace = null;
	
	function conceito($name_Parameter, $descrition_Parameter){
		$this->name = $name_Parameter;
		$this->descrition = $descrition_Parameter;
		$this->relations = array();
		$this->subConception = array(); 
		$this->namespace = "";
	}
}

class relacao_entre_conceitos
{
	var $predicate;
	var $verb;
	
	function relacao_entre_conceitos($parameter_Predicate, $parameter_Verv){
		$this->predicade[] = $parameter_Predicate;
		$this->verb = $parameter_Verv;
	}
}

class termo_do_lexico
{
	var $name = null;
	var $nocao = null;
	var $impact = null;
	
	function termo_do_lexico($name_Parameter_Term, $notion_Parameter,
                $impact_Parameter){
		$this->nome = $name_Parameter_Term;
		$this->nocao = $notion_Parameter;
		$this->impact = $impact_Parameter;
	}
}

?>