<?php

include_once 'estruturas.php';
include_once 'auxiliar_algoritmo.php';
include_once 'bd.inc';

session_start();


function get_lista_de_sujeito(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select * from lexico where tipo = 'sujeito' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH))	{
            
		$aux[] = obter_termo_do_lexico($line);
                
	}
	
	sort($aux);
	
	return $aux;

	
}

function get_lista_de_objeto(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query 	= "select * from lexico where tipo = 'objeto' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH))	{
            
		$aux[] = obter_termo_do_lexico($line);
	}
        
	
	sort($aux);
	
	return $aux;
	
}

function get_lista_de_verbo(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select * from lexico where tipo = 'verbo' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH)){
            
		$aux[] = obter_termo_do_lexico($line);
                
	}
	
	sort($aux);
	
	return $aux;
	
}

function get_lista_de_estado(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select * from lexico where tipo = 'estado' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH))	{
            
		$aux[] = obter_termo_do_lexico($line);
                
	}
	
	sort($aux);
	
	return $aux;
	
}

function verifica_tipo(){
    
	$id_project = $_SESSION['id_projeto'];
        
	//This function verifys if all the members of the lexicon table have a defined type
	//In case there is registers in the table without a defined type, the function returns this registers
	//Otherwise it returns true
	
	$query = "select * from lexico where tipo is null AND id_projeto='$id_project' order by id_lexico;";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	$result2 = mysql_num_rows($result);
	
	$col_value = $result2;
	
	if($col_value>0){
            
		// In case there ar undefined lexicon types, its ids will be returned as an array
		
		$aux = array();
		
		while ($line2 = mysql_fetch_array($result, MYSQL_ASSOC)){
                    
			$aux[] = $line2['id_lexico'];
                        
		}
                
		mysql_free_result($result);
                
		return($aux);
	}else{
            
		mysql_free_result($result);
                
		return(TRUE);
                
	}
	
}

function atualiza_tipo($id_lexicon, $type){
    
	$id_project = $_SESSION['id_projeto'];
	// This function refreshes the lexicon type $id_lexicon to $type
	// This function only accepts the types: subject, object, verb, state and NULL
	
	if(!(($type != "sujeito")||($type != "objeto")||($type != "verbo")||($type != "estado")||($type != "null"))){
		return (FALSE);             
	}
        
	if($type == "null"){
		$query = "update lexico set tipo = $type where id_lexico = '$id_lexicon';";
	}else{
		$query = "update lexico set tipo = '$type' where id_lexico = '$id_lexicon';";
	}
	
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	return(TRUE);
        
	
}

function obter_lexico($id_lexicon){
    
	$id_project = $_SESSION['id_projeto'];
	// Returns all the fields of the lexicon; each field is a position in the array
	// that can be indexed for the field name or by the entire index
	
        
	$query  = "select * from lexico where id_lexico = '$id_lexicon' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	$line  = mysql_fetch_array($result, MYSQL_BOTH);
        
	return($line);
}

function obter_termo_do_lexico($lexicon){
    
	$id_project = $_SESSION['id_projeto'];
	$impactos   = array();
	$id_lexicon  = $lexicon['id_lexico'];
	$query	    = "select impacto from impacto where id_lexico = '$id_lexicon'";
	$result     = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	while($line = mysql_fetch_array($result, MYSQL_ASSOC))	{
            
		$impactos[] = strtolower($line['impacto']);
                
	}
        
	$termo_do_lexico = new termo_do_lexico(strtolower($lexicon['nome']), strtolower($lexicon['nocao']), $impactos);
	return $termo_do_lexico;
        
}

/*
function zera_tipos()
{
$query = "update lexico set tipo =  NULL;";
$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
}
*/

function cadastra_impacto($id_lexicon, $impacto){
    
    
	$id_project = $_SESSION['id_projeto']; 
        
	$query_insert_impact  = "insert into impacto (id_lexico, impacto) values ('$id_lexicon', '$impacto');";
	$result = mysql_query($query_insert_impact) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	$query_select_impact  = "select * from impacto where impacto = '$impacto' and id_lexico = $id_lexicon;";
	$result = mysql_query($query_select_impact) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$id_impacto = $line['id_impacto'];
	
	return $id_impacto;
}

// Create concepts table
function get_lista_de_conceitos(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select * from conceito where id_projeto='$id_project';";
	$result1 = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result1, MYSQL_BOTH))	{
            
		$conc = new conceito($line['nome'], $line['descricao'] );
		$conc->namespace = $line['namespace'];
		
		$id = $line['id_conceito'];
		$query = "select * from relacao_conceito where id_conceito = '$id' AND id_projeto='$id_project';";
		$result2 = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
                
		while ($line2 = mysql_fetch_array($result2, MYSQL_BOTH)){
                    
			$idrel = $line2['id_relacao'];
			$query = "select * from relacao where id_relacao = '$idrel' AND id_projeto='$id_project';";
			$result3 = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
			$line3 = mysql_fetch_array($result3, MYSQL_BOTH);
			$rel = $line3['nome'];
			$pred = $line2['predicado'];
			$indice = existe_relacao($rel, $conc->relacoes);
			if( $indice != -1 ){
                            
				$conc->relacoes[$indice]->predicados[] = $pred;
			}else{
                            
				$conc->relacoes[] = new relacao_entre_conceitos($pred, $rel);
                                
			}
		}
                
		$aux[] = $conc;
	}
        
	sort($aux);
	
	$query_hierarchy = "select * from hierarquia where id_projeto='$id_project';";
	$result_hierarchy = mysql_query($query_hierarchy) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	while ($line = mysql_fetch_array($result_hierarchy, MYSQL_BOTH)){
            		
		$id_concept = $line['id_conceito'];
		$query_concept = "select * from conceito where id_conceito = '$id_concept' AND id_projeto='$id_project';";
		$result_concept = mysql_query($query_concept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
		$line_concept = mysql_fetch_array($result_concept, MYSQL_BOTH);
		$conceito_nome = $line_concept['nome'];
                
		
		$id_subconcept = $line['id_subconceito'];
		$query_subconcept = "select * from conceito where id_conceito = '$id_subconcept' AND id_projeto='$id_project';";
		$result_subconcept = mysql_query($query_subconcept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
		$line_subconcept = mysql_fetch_array($result_subconcept, MYSQL_BOTH);
		$subconceito_nome = $line_subconcept['nome'];
                
		
		foreach ($aux as $key=>$conc1){
                    
			if($conc1->nome == $conceito_nome){
                            
				$aux[$key]->subconceitos[] = $subconceito_nome;
                                
			}
		}
		
		
	}
	
	
	return $aux;
}

//Create concepts table
function get_lista_de_relacoes(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select nome from relacao where id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH)){
            
		$aux[] = $line['nome'];
                
	}
	
	sort($aux);
	
	return $aux;
}

// Create axioms table
function get_lista_de_axiomas(){
    
	$id_project = $_SESSION['id_projeto'];
	$aux = array();
	
	$query = "select axioma from axioma where id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH)){
            
		$aux[] = $line['axioma'];
                
	}
	
	sort($aux);
	
	return $aux;
}

// Variable function
function get_funcao(){
    
	$id_project = $_SESSION['id_projeto'];
	
	$query = "select valor from algoritmo where nome = 'funcao' AND id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	$line = mysql_fetch_array($result, MYSQL_BOTH);
        
	return $line['valor'];
}

// Index variables
function get_indices(){
    
	$id_project = $_SESSION['id_projeto'];
	
	$query = "select * from algoritmo where id_projeto='$id_project';";
	$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
	$indice = array();
	
	while ($line = mysql_fetch_array($result, MYSQL_BOTH)){
            
		$indice[$line['nome']] = $line['valor'];
                
	}
        
	return $indice;
}

function salvar_algoritmo(){
    
	$id_project = $_SESSION['id_projeto'];
	$link = bd_connect();
	
	foreach ($_SESSION["lista_de_conceitos"] as $conceit){
            
		print($conceit->nome);
		foreach ($conceit->relacoes as $rel){
                    
			print("<br>----$rel->verbo");
                        
			foreach ($rel->predicados as $pred){
                            
				print("<br>--------$pred");
                                
			}
		}
	}
	
	
	$query_relation = "delete from relacao where id_projeto='$id_project';";
	$result = mysql_query($query_relation) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	$query_concepts = "delete from conceito where id_projeto='$id_project';";
	$result = mysql_query($query_concepts) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	$query_relation_concept = "delete from relacao_conceito where id_projeto='$id_project';";
	$result = mysql_query($query_relation_concept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	$query_axioms = "delete from axioma where id_projeto='$id_project';";
	$result = mysql_query($query_axioms) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	$query_algorithm = "delete from algoritmo where id_projeto='$id_project';";
	$result = mysql_query($query_algorithm) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	$query_hierarchy = "delete from hierarquia where id_projeto='$id_project';";
	$result = mysql_query($query_hierarchy) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
        
	
	if( isset($_SESSION["lista_de_relacoes"]) ){
            
		foreach ($_SESSION["lista_de_relacoes"] as $relation){
                    
			$query  = "insert into relacao (nome, id_projeto) values ('$relation', '$id_project');";
			$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                        
		}
	}
        
	if( isset($_SESSION["lista_de_conceitos"]) ){
            
		foreach ($_SESSION["lista_de_conceitos"] as $conc){ 
                    
			$query  = "select id_conceito from conceito where nome = '$conc->nome' and id_projeto='$id_project';";
			$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                        
			$id_concept = 0;
			if( mysql_num_rows($result) > 0 ){ 
                            
				$line = mysql_fetch_array($result, MYSQL_BOTH);
				$id_concept = $line['id_conceito'];
                                
			}else{	
                            
				$query_insert_concept  = "insert into conceito (nome,descricao,namespace, id_projeto) values ('$conc->nome', '$conc->descricao','$conc->namespace' ,'$id_project');";
				$result = mysql_query($query_insert_concept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
				
				$query_select_concept  = "select id_conceito from conceito where nome = '$conc->nome' and id_projeto='$id_project';";
				$result = mysql_query($query_select_concept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                                
				$line = mysql_fetch_array($result, MYSQL_BOTH);
				$id_concept = $line['id_conceito'];
			}
			
			
			foreach ($conc->relacoes as $relation){
                            
				$verb = $relation->verbo;
				$query  = "select id_relacao from relacao where nome = '$verb' and id_projeto='$id_project';";
				$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
				$line = mysql_fetch_array($result, MYSQL_BOTH);
				$id_relation = $line['id_relacao'];
				$predicados = $relation->predicados;
                                
                                
				foreach ($predicados as $pred){
                                    
					$query  = "insert into relacao_conceito (id_conceito,id_relacao,predicado,id_projeto) values ('$id_concept', '$id_relation', '$pred', '$id_project');";
					$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                                        
				}
			}
		}
                
                
		foreach ($_SESSION["lista_de_conceitos"] as $conc){
                    
			foreach ($conc->subconceitos as $subconceito){
                            
				if( $subconceito != -1 ){
                                    
					$query_subconcepts  = "select id_conceito from conceito where nome = '$subconceito' and id_projeto='$id_project';";
					$result_subconcepts = mysql_query($query_subconcepts) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                                        $line_subconcepts = mysql_fetch_array($result_subconcepts, MYSQL_BOTH);
					
                                        $id_subconcept = $line_subconcepts['id_conceito'];
					
					$name = $conc->nome;
                                        
					$query_select_concept  = "select id_conceito from conceito where nome = '$nome' and id_projeto='$id_project';";
					$result_select_concept = mysql_query($query_select_concept) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                                        
					$line = mysql_fetch_array($result_select_concept, MYSQL_BOTH);
					$id_concept = $line['id_conceito'];
					
					$query  = "insert into hierarquia (id_conceito,id_subconceito,id_projeto) values ('$id_concept', '$id_subconcept','$id_project');";
					$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                                        
				}
			}
		}
	}
        
	if( isset($_SESSION["lista_de_axiomas"]) ){
            
		foreach ($_SESSION["lista_de_axiomas"] as $axioma){
                    
			$query  = "insert into axioma (axioma,id_projeto) values ( '$axioma','$id_project' );";
			$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
		}
	}
        
	if( isset($_SESSION["funcao"]) ){
            
		$func = $_SESSION['funcao'];
		$query  = "insert into algoritmo (nome, valor, id_projeto) values ('funcao'," ;
		$query = $query . "'" . $func . "', '$id_project' );";
		$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
	}
        
	if( isset($_SESSION["index1"]) ){
            
		$query  = "insert into algoritmo (nome, valor,id_projeto) values ('index1',";
		$query = $query . "'" . $_SESSION['index1'] . "', '$id_project');";
		$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
	}
        
	if( isset($_SESSION["index3"]) ){
            
		$query  = "insert into algoritmo (nome, valor, id_projeto) values ('index3',";
		$query = $query . "'" . $_SESSION['index3'] . "', '$id_project');";
		$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
	}
        
	if( isset($_SESSION["index4"]) ){
            
		$query  = "insert into algoritmo (nome, valor, id_projeto) values ('index4',";
		$query = $query . "'" . $_SESSION['index4'] . "', '$id_project');";
		$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
	}
        
	if( isset($_SESSION["index5"]) ){
            
		$query  = "insert into algoritmo (nome, valor, id_projeto) values ('index5',";
		$query = $query . "'" . $_SESSION['index5'] . "', '$id_project');";
		$result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
                
	}
        
	mysql_close($link);
	
	if( $_SESSION["funcao"] != 'fim' ){
            
		?>
	<script>
	document.location = "auxiliar_interface.php";
	</script>
	<?php
	}else{
            
            		?>
            <script>
            document.location = "algoritmo.php";
            </script>
            <?php
        
        
	}
        
}

if( isset( $_SESSION["tipos"] )){
    
	session_unregister( "tipos" );
	
	include_once 'bd.inc';
	
	$link = bd_connect();
	
	$list = verifica_tipo();
	
	foreach( $list as $key=>$termo){
            
		$aux = $_POST["type" . $key];
		echo ("$termo, $aux <br>");
                
		if( ! atualiza_tipo($termo, $aux) ){
                    
			echo "ERRO <br>";
		}
	}
	
	mysql_close($link);
			?>
	<script>
	document.location = "algoritmo_inicio.php";
	</script>
	<?php
}

if( array_key_exists("save", $_POST )){
    
	salvar_algoritmo();
}


?>

<html>
  <head>
    <title>Auxiliar BD</title>
    <style>

    </style>
  </head>
<body>
</body>
</html>