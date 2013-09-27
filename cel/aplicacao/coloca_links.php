<?php

function carrega_vetor_lexicos($id_project, $id_current_lexicon, $semAtual) {

    $lexicon_vector = array();

    if ($semAtual) {

        $lexicon_query = "SELECT id_lexicon, name    
							FROM lexicon    
							WHERE id_project = '$id_project' AND id_lexicon <> '$id_current_lexicon' 
							ORDER BY name DESC";

        $synonyms_query = "SELECT id_lexicon, name 
							FROM sinonimo
							WHERE id_project = '$id_project' AND id_lexicon <> '$id_current_lexicon' 
							ORDER BY name DESC";
    } else {

        $lexicon_query = "SELECT id_lexicon, name    
							FROM lexicon    
							WHERE id_project = '$id_project' 
							ORDER BY name DESC";

        $synonyms_query = "SELECT id_lexicon, name    
							FROM sinonimo
							WHERE id_project = '$id_project' ORDER BY name DESC";
    }

    $lexicon_query_result = mysql_query($lexicon_query) or die("Erro ao enviar a query de selecao na tabela lexicos !" . mysql_error());

    $i = 0;
    
    while ($lexicon_line = mysql_fetch_object($lexicon_query_result)) {

        $lexicon_vector[$i] = $lexicon_line;
        $i++;
        
    }

    $synonyms_query_result = mysql_query($synonyms_query) or die("Erro ao enviar a query de selecao na tabela sinonimos !" . mysql_error());
    
    while ($linhaSinonimo = mysql_fetch_object($synonyms_query_result)){
        
        $lexicon_vector[$i] = $linhaSinonimo;
        $i++;
        
    }
    
    return $lexicon_vector;
}


function carrega_vetor_cenario($id_project, $id_current_scenario, $semAtual){
    
    if (!isset($scenarios_vector)){
        
        $scenarios_vector = array();
        
    }
    
    if ($semAtual){
        
        $query_scenarios = "SELECT id_scenario, title    
							FROM scenario    
							WHERE id_project = '$id_project' AND id_scenario <> '$id_current_scenario' 
							ORDER BY title DESC";
    } else{
        
        $query_scenarios = "SELECT id_scenario, title    
							FROM scenario    
							WHERE id_project = '$id_project' 
							ORDER BY title DESC";
        
    }

    $query_scenario_result = mysql_query($query_scenarios) or die("Erro ao enviar a query de selecao !!" . mysql_error());

    $i = 0;
    
    while ($scenario_row = mysql_fetch_object($query_scenario_result)){
        
        $scenarios_vector[$i] = $scenario_row;
        $i++;
        
    }

    return $scenarios_vector;
}

// Divides the array in two
function divide_array(&$vector, $begin, $end, $type){
    
    $dir = 1;

    while ($begin < $end){
        
        if (strcasecmp($type, 'scenario') == 0){
            
            if (strlen($vector[$begin]->title) < strlen($vector[$end]->title)) {
                
                $str_temp = $vector[$begin];
                $vector[$begin] = $vector[$end];
                $vector[$end] = $str_temp;
                $dir--;
                
            }
            
        }else{
            
            if (strlen($vector[$begin]->name) < strlen($vector[$end]->name)){
                
                $str_temp = $vector[$begin];
                $vector[$begin] = $vector[$end];
                $vector[$end] = $str_temp;
                $dir--;
                
            }
        }
        
        if ($dir == 1){
           $end--; 
        }else{
           $begin++; 
        }
            
    }

    return $begin;
}

// Sort the vector

function quicksort(&$vector, $begin, $end, $type){
    
    if ($begin < $end){
        
        $k = divide_array($vector, $begin, $end, $type);
        
        quicksort($vector, $begin, $k - 1, $type);
        quicksort($vector, $k + 1, $end, $type);
        
    }
}

// Function that construct the links according to the text, passed through parameters $text, $lexicon passed through
// the parameters $lexicon_vector, and scenario passed through the parameter $scenario_vector   

function monta_links($text, $lexicon_vector, $scenarios_vector){
    
    $copy_text = $text;
    
    if (!isset($aux_lexicon_vector)){
        
        $aux_lexicon_vector = array();
    }
    
    if (!isset($aux_scenarios_vector)){
        
        $aux_scenarios_vector = array();
        
    }
    
    if (!isset($scenarios_vector)){
        
        $scenarios_vector = array();
        
    }
    
    if (!isset($lexicon_vector)){
        
        $lexicon_vector = array();
        
    }

    // If the lexicon vector is empty, it will only look for references to lexicons

    if (count($scenarios_vector) == 0) {

        $i = 0;
        $a = 0;
        
        while ($i < count($lexicon_vector)){
            
            $name_lexicon = escapa_metacaracteres($lexicon_vector[$i]->name);
            $regex = "/(\s|\b)(" . $name_lexicon . ")(\s|\b)/i";
            
            if (preg_match($regex, $copy_text) != 0){
                
                $copy_text = preg_replace($regex, " ", $copy_text);
                $aux_lexicon_vector[$a] = $lexicon_vector[$i];
                
                $a++;
                
            }
            $i++;
            
        }
    } else{
        

        // If the scenario vector isnt empty, it will look for lexicons and scenarios

        $size_lexicons = count($lexicon_vector);
        $size_scenarios = count($scenarios_vector);
        $tamanhoTotal = $size_lexicons + $size_scenarios;
        
        $i = 0;
        $j = 0;
        $a = 0;
        $b = 0;
        $contador = 0;
        
        while ($contador < $tamanhoTotal){
            
            if (($i < $size_lexicons ) && ($j < $size_scenarios)){
                
                if (strlen($scenarios_vector[$j]->title) < strlen($lexicon_vector[$i]->name)){
                    
                    $name_lexicon = escapa_metacaracteres($lexicon_vector[$i]->name);
                    $regex = "/(\s|\b)(" . $name_lexicon . ")(\s|\b)/i";
                    
                    if (preg_match($regex, $copy_text) != 0){
                        
                        $copy_text = preg_replace($regex, " ", $copy_text);
                        $aux_lexicon_vector[$a] = $lexicon_vector[$i];
                        $a++;
                        
                    }
                    
                    $i++;
                    
                }else{

                    $title_scenarios = escapa_metacaracteres($scenarios_vector[$j]->title);
                    $regex = "/(\s|\b)(" . $title_scenarios . ")(\s|\b)/i";
                    
                    if (preg_match($regex, $copy_text) != 0){
                        
                        $copy_text = preg_replace($regex, " ", $copy_text);
                        $aux_scenarios_vector[$b] = $scenarios_vector[$j];
                        $b++;
                        
                    }
                    
                    $j++;
                }
                
            }else if($size_lexicons == $i){

                $title_scenarios = escapa_metacaracteres($scenarios_vector[$j]->title);
                $regex = "/(\s|\b)(" . $title_scenarios . ")(\s|\b)/i";
                
                if (preg_match($regex, $copy_text) != 0){
                    
                    $copy_text = preg_replace($regex, " ", $copy_text);
                    $aux_scenarios_vector[$b] = $scenarios_vector[$j];
                    $b++;
                    
                }
                
                $j++;
                
            }else if($size_scenarios == $j){

                $name_lexicon = escapa_metacaracteres($lexicon_vector[$i]->name);
                $regex = "/(\s|\b)(" . $name_lexicon . ")(\s|\b)/i";
                
                if (preg_match($regex, $copy_text) != 0){
                    
                    $copy_text = preg_replace($regex, " ", $copy_text);
                    $aux_lexicon_vector[$a] = $lexicon_vector[$i];
                    $a++;
                    
                }
                
                $i++;
            }
            
            $contador++;
        }
    }
    
    //print_r( $aux_lexicon_vector );
    // Adiciona os links para lexicos no texto 

    $index = 0;
    $vetorAux = array();
    
    while ($index < count($aux_lexicon_vector)){
        
        $name_lexicon = escapa_metacaracteres($aux_lexicon_vector[$index]->name);
        $regex = "/(\s|\b)(" . $name_lexicon . ")(\s|\b)/i";
        $link = "<a title=\"L�xico\" href=\"main.php?t=l&id=" . $aux_lexicon_vector[$index]->id_lexicon . "\">" . $aux_lexicon_vector[$index]->name . "</a>";
        $vetorAux[$index] = $link;
        $text = preg_replace($regex, "$1wzzxkkxy" . $index . "$3", $text);
        $index++;
    }
    
    $index2 = 0;

    while ($index2 < count($vetorAux)){
        
        $linkLexico = ( $vetorAux[$index2] );
        $regex = "/(\s|\b)(wzzxkkxy" . $index2 . ")(\s|\b)/i";
        $text = preg_replace($regex, "$1" . $linkLexico . "$3", $text);
        $index2++;
    }


    // Adiciona os links para cen�rios no texto 

    $index = 0;
    $vetorAuxCen = array();
    
    while ($index < count($aux_scenarios_vector)){
        
        $title_scenarios = escapa_metacaracteres($aux_scenarios_vector[$index]->title);
        $regex = "/(\s|\b)(" . $title_scenarios . ")(\s|\b)/i";
        $link = "$1<a title=\"Cen�rio\" href=\"main.php?t=c&id=" . $aux_scenarios_vector[$index]->id_scenario . "\"><span style=\"font-variant: small-caps\">" . $aux_scenarios_vector[$index]->title . "</span></a>$3";
        $vetorAuxCen[$index] = $link;
        $text = preg_replace($regex, "$1wzzxkkxyy" . $index . "$3", $text);
        $index++;
        
    }


    $index2 = 0;
    
    while ($index2 < count($vetorAuxCen)){
        
        $linkCenario = ( $vetorAuxCen[$index2] );
        $regex = "/(\s|\b)(wzzxkkxyy" . $index2 . ")(\s|\b)/i";
        $text = preg_replace($regex, "$1" . $linkCenario . "$3", $text);
        $index2++;
        
    }

    return $text;
}
?>

