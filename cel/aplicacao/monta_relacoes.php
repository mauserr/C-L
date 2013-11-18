<?php
include_once("monta_relacoes.php");
include_once("coloca_links.php");

// Riding the relations used int side menu

function mount_relations($id_project)
{
	
	assert($id_project != NULL);
	
	// Deletes all relations existing tables centocen, centolex and lextolex
	
	$DB = new PGDB () ;
    $sql1 = new QUERY ($DB) ;
    $sql2 = new QUERY ($DB) ;
    $sql3 = new QUERY ($DB) ;
    
    //$sql1->execute ("DELETE FROM centocen");
    //$sql2->execute ("DELETE FROM centolex") ;
    //$sql3->execute ("DELETE FROM lextolex") ;

	// Redo the table relationships centocen, centolex and lextolex

	//selects all scenarios
	
	$q = "SELECT *
	          FROM scenario
	          WHERE id_project = $id_project
	          ORDER BY CHAR_LENGTH(title) DESC";
	$qrr = mysql_query($q) or die("Erro ao enviar a query");   
	
	while ($result = mysql_fetch_array($qrr)) // Para todos os cenarios 
	{    
		
		$id_current_scenario = $result['id_scenario'];
		
		// Mount vector title scenarios
		$vector_scenarios = carrega_vetor_cenario($id_project, $id_current_scenario);
		
		// Mount vector name and synonyms of all lexical
		$vector_lexicons = carrega_vetor_todos ($id_project);
		
		// Sort the vector of the number of lexical palavaras name or synonym
		quicksort($vector_lexicons, 0, count($vector_lexicons)-1,'lexico' );
		
		//Sort the array of scenarios by the number of words of the title
		quicksort($vector_scenarios, 0, count($vector_scenarios)-1,'cenario' );
		
		//Title
		
		$title = $result['titulo'];
		$tempTitle = cenario_para_lexico($id_current_scenario, $title, $vector_lexicons);
		adiciona_relacionamento($id_current_scenario,'cenario', $tempTitle);
		
		// Objective
		
		$objective = $result['objetivo'];
		$tempObjective = cenario_para_lexico($id_current_scenario, $objective, $vector_lexicons);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempObjective);
		
		// Context
		
		$context = $result['contexto'];
		$tempContext = cenario_para_lexico_cenario_para_cenario($id_current_scenario, $context, $vector_lexicons, $vector_scenarios);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempContext);
		
		// Actors 
		
		$actors = $result['atores'];
		$tempActors = cenario_para_lexico($id_current_scenario, $actors, $vector_lexicons);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempActors);
		
		// Resources 
		
		$resources = $result['recursos'];
		$tempResources = cenario_para_lexico($id_current_scenario, $resources, $vector_lexicons);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempResources);
		
		// Exception
		
		$exception = $result['excecao'];
		$tempException = cenario_para_lexico($id_current_scenario, $exception, $vector_lexicons);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempException);
		
		// Episodes
		
		$episodes = $result['episodios'];
		$tempEpisodes = cenario_para_lexico_cenario_para_cenario($id_current_scenario, $episodes, $vector_lexicons, $vector_scenarios);
		adiciona_relacionamento($id_current_scenario, 'cenario', $tempEpisodes);
	}
	
	// Selects all lexicons
	
	$q = "SELECT *
	          FROM lexicon
	          WHERE id_project = $id_project
	          ORDER BY CHAR_LENGTH(nome) DESC";
	$qrr = mysql_query($q) or die("Erro ao enviar a query");   
	
	while ($result = mysql_fetch_array($qrr)) // For all the lexical
	{   
		
		$id_current_lexicon = $result['id_lexico'];
		
		// Mount vector names and synonyms of all lexical minus current lexicon
		$vector_lexicons = carrega_vetor($id_project, $id_current_lexicon);
		
		// Sort the vector of the number of lexical palavaras name or synonym
		quicksort($vector_lexicons, 0, count($vector_lexicons)-1,'lexico' );
		
		// Notion
		
		$notion = $result['nocao'];
		$tempNotion = lexico_para_lexico($id_lexicon, $notion, $vector_lexicons);
		adiciona_relacionamento($id_current_lexicon, 'lexico', $tempNotion);
		
		// Impact	
	
		$impact = $result['impacto'];
		$tempImpact = lexico_para_lexico($id_lexicon, $impact, $vector_lexicons);
		adiciona_relacionamento($id_current_lexicon, 'lexico', $tempImpact);
	} 
}

// Brand relationships to lexical lexical
function lexico_para_lexico($id_lexicon, $text, $vector_lexicons)
{
	
	assert($id_lexicon != NULL);
	assert($text != NULL);
	assert($vector_lexicons != NULL);
	
	$i=0;
	
    while( $i < count($vector_lexicons) )
    {
        $regex = "/(\s|\b)(" . $vector_lexicons[$i]->name . ")(\s|\b)/i";
        $text = preg_replace( $regex, "$1{l".$vector_lexicons[$i]->id_lexicon."**$2"."}$3", $text );
        $i++;
        
    	// enter the relationship in the table centolex
        //$q = "INSERT 
        //		INTO lextolex (id_lexico_from, id_lexico_to)
        //		VALUES ($id_lexico, " . $vetor_lexicos[$i]->id_lexico . ")";
        //mysql_query($q) or die("Erro ao enviar a query de INSERT na lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
    }
    return $text;
}

// Brand relationships scenarios for lexical

function cenario_para_lexico($id_scenario, $text, $vector_lexicons)
{
    
    assert($id_scenario != NULL);
    assert($text != NULL);
	assert($vector_lexicons != NULL);
    
    $i=0;
    
    while($i < count($vector_lexicons))
    {
     	$regex = "/(\s|\b)(" . $vetor_lexicons[$i]->name . ")(\s|\b)/i";
        $text = preg_replace( $regex, "$1{l".$vetor_lexicos[$j]->id_lexicon."**$2"."}$3", $text );
        $i++;
       	// enter the relationship in the table centolex
        //$q = "INSERT 
        //		INTO centolex (id_cenario, id_lexico)
        //		VALUES ($id_cenario, " . $vetor_lexicos[$i]->id_lexico . ")";
        //mysql_query($q) or die("Erro ao enviar a query de INSERT na centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
    }
    return $text;
}

// Brand relationships scenarios for scenario
	
function cenario_para_cenario($id_scenario, $text, $vector_scenarios)
{
    
    assert($id_scenario != NULL);
    assert($text != NULL);
	assert($vector_scenarios != NULL);
    
    $i=0;
    
    while( $i < count($vector_scenarios))
    {
     	$regex = "/(\s|\b)(" . $vector_scenarios[$i]->title . ")(\s|\b)/i";
        $text = preg_replace( $regex, "$1{c".$vector_scenarios[$j]->id_scenario."**$2"."}$3", $text);
        $i++;
        
       	// enter the relationship in the table centolex
        //$q = "INSERT 
        //		INTO centolex (id_cenario, id_lexico)
        //		VALUES ($id_cenario, " . $vetor_lexicos[$i]->id_lexico . ")";
        //mysql_query($q) or die("Erro ao enviar a query de INSERT na centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
    }
    return $text;
}

// Marca as relaçoes de cenário para cenário e cenário para léxico no mesmo texto

function cenario_para_lexico_cenario_para_cenario($id_scenario, $text, $vector_lexicons, $vector_scenarios)
{
    
    assert($id_scenario != NULL);
    assert($text != NULL);
    assert($vector_lexicons != NULL);
	assert($vector_scenarios != NULL);
	
    $i=0;
    $j=0;
    $k=0;
    
    $total = count($vector_lexicons) + count($vector_scenarios);
    while( $k < $total )
    {
        if(strlen($vector_scenarios[$j]->title) < strlen($vector_lexicons[$i]->name))
    	{
    		$regex = "/(\s|\b)(" . $vetor_lexicos[$i]->nome . ")(\s|\b)/i";
			$texto = preg_replace( $regex, "$1{l".$vetor_lexicos[$i]->id_lexico."**$2"."}$3", $texto );
       		$i++;
       		
       		// enter the relationship in the table centolex
        	//$q = "INSERT 
        	//		INTO centolex (id_cenario, id_lexico)
        	//		VALUES ($id_cenario, " . $vetor_lexicos[$i]->id_lexico . ")";
        	//mysql_query($q) or die("Erro ao enviar a query de INSERT na centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
        	
    	}else{
        	$regex = "/(\s|\b)(" . $vetor_scenarios[$j]->title . ")(\s|\b)/i";
           	$text = preg_replace( $regex, "$1{c".$vector_scenarios[$j]->id_scenario."**$2"."}$3", $text);
    	    $j++;
    	}
        $k++;
    }   
    return $text;
}

// Function that adds tables centocen relationships, and centolex lextolex
// Through the analysis of brands
// Id id_from lexicon or scenario references another scenario or lexical
// $ Tipo_from whom this type of referencing (whether lexical or scenario)
function adiciona_relacionamento($id_from, $type_from, $text)
{
    
    assert($id_from != NULL);
    assert($type_from != NULL);
    assert($text != NULL);
    
    $i = 0; // Index of bulleted text
    $parser = 0; // Checks should be added when the tags
    
    $new_text = "";
    
    while($i < strlen(&$text))
    {    
        if($text[$i] == "{" )
        {
            $parser++;
            if( $parser == 1 ) //add link to text - opening
            {
                 $id_to = "";
                 $i++;
                 $type= $text[$i];
                 $i++;
                 while($text[$i] != "*")
                 {
                    $id_to .= $text[$i];
                 	$i++;	
                 }
                 if($type=="l") // Destiny is a lexicon (id_lexico_to)
                 {
                 	 if(strcasecmp($type_from,'lexico') == 0 ) // Origin is a lexicon (id_lexico_from -> id_lexico_to)
                 	 {
                 	 	echo '<script language="javascript">confirm(" '.$id_from.' - '.$id_to.'léxico para léxico")</script>';
                 	 	// Add relationship lexicon to lexicon	
                 	 }else if(strcasecmp($type_from,'cenario') == 0) // Origin is a scenario (id_cenario -> id_lexico)
                 	 {
                 	 	echo '<script language="javascript">confirm(" '.$id_from.' - '.$id_to.'cenário para léxico")</script>';
                 	 	// Add relationship scenario to lexicon
                 	 }
                 }
                 if($type=="c") // Destiny is a scenario (id_cenario_to)
                 {
                     if(strcasecmp($type_from,'cenario') == 0) // Origin is a scenario (id_cenario_from -> id_cenario_to)
                     {
                 	 	echo '<script language="javascript">confirm(" '.$id_from.' - '.$id_to.'cenário para cenário")</script>';
                        // Relationships type setting for scenario
                        // Adds relation of scenery to the scenery table centocen
                 	 	//$q = "INSERT 
				      	//		INTO centocen (id_cenario_from, id_cenario_to)
				       	//		VALUES ($id_from, " . $vetor_cenarios[$j]->id_cenario . ")";
				       	//mysql_query($q) or die("Erro ao enviar a query de INSERT na centocen<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                 	 }
                 }
                 $i+1;
            }
        }elseif($text[$i] == "}")
        {
            $parser--; 
        }
        $i++;
    }
}   
 

?>