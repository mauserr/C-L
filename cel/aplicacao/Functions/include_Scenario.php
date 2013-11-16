<?php

/*************************************************************
 * File: /Functions/scenario_Functions.php
 * purpose: Insert a scenario in the data Base. It gets the id_projeto,
 * titulo, objetivo, contexto, atores, recursos, excessão e episodios as
 * parameters. Returns id_cenario
 * 
 ************************************************************/

if (!(function_exists("include_Scenario"))) {
    function include_Scenario($id_project, $title, $objective, $context, $actors, $resources, $exception, $episodes)
    {
        assert(is_string($episodes));
        assert(is_string($title));
        assert(is_string($objective));
        assert(is_string($context));
        assert(is_string($actors));
        assert(is_string($resources));
        assert(is_string($exception));
        assert($id_project !=NULL);
        assert( $title!=NULL);
        assert($objective !=NULL);
        assert($context !=NULL);
        assert($actors !=NULL);
        assert($resources !=NULL);
        assert($exception !=NULL);
        assert($episodes !=NULL);
        
        //Variavel $connect que faz conexao com a base de dados
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $data = date("Y-m-d");
        
        $query = "INSERT INTO scenario (id_project,data, title, objective, context, actors, resource, exception, episodes) 
		VALUES ($id_project,'$data', '".data_prepare(strtolower($title))."', '".data_prepare($objective)."',
		'".data_prepare($context)."', '".data_prepare($actors)."', '".data_prepare($resources)."',
		'".data_prepare($exception)."', '".data_prepare($episodes)."')";
			  
	mysql_query($query) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $query = "SELECT max(id_scenario) FROM scenario";
        
        $query_result = mysql_query($query) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $result = mysql_fetch_row($query_result);
        return $result[0];
    }
}

?>
