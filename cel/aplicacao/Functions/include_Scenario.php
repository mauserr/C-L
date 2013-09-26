<?php

/*************************************************************
 * File: /Functions/include_Scenario.php
 * purpose: Insert a scenario in the data Base. It gets the id_projeto,
 * titulo, objetivo, contexto, atores, recursos, excessão e episodios as
 * parameters. Returns id_cenario
 * 
 ************************************************************/

if (!(function_exists("include_Scenario"))) {
    function include_Scenario($id_projeto, $titulo, $objetivo, $contexto, $atores, $recursos, $excecao, $episodios)
    {
        //Variavel $connect que faz conexao com a base de dados
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $data = date("Y-m-d");
        
        $q = "INSERT INTO cenario (id_projeto,data, titulo, objetivo, contexto, atores, recursos, excecao, episodios) 
		VALUES ($id_projeto,'$data', '".data_prepare(strtolower($titulo))."', '".data_prepare($objetivo)."',
		'".data_prepare($contexto)."', '".data_prepare($atores)."', '".data_prepare($recursos)."',
		'".data_prepare($excecao)."', '".data_prepare($episodios)."')";
			  
	mysql_query($q) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $q = "SELECT max(id_cenario) FROM cenario";
        
        $qrr = mysql_query($q) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $result = mysql_fetch_row($qrr);
        return $result[0];
    }
}

?>
