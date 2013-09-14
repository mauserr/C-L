<?php

function carrega_vetor_lexicos($id_project, $id_current_lexicon, $semAtual) {

    $lexicon_vector = array();

    if ($semAtual) {

        $lexicon_query = "SELECT id_lexico, nome    
							FROM lexico    
							WHERE id_projeto = '$id_project' AND id_lexico <> '$id_current_lexicon' 
							ORDER BY nome DESC";

        $synonyms_query = "SELECT id_lexico, nome 
							FROM sinonimo
							WHERE id_projeto = '$id_project' AND id_lexico <> '$id_current_lexicon' 
							ORDER BY nome DESC";
    } else {

        $lexicon_query = "SELECT id_lexico, nome    
							FROM lexico    
							WHERE id_projeto = '$id_project' 
							ORDER BY nome DESC";

        $synonyms_query = "SELECT id_lexico, nome    
							FROM sinonimo
							WHERE id_projeto = '$id_project' ORDER BY nome DESC";
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


function carrega_vetor_cenario($id_project, $id_cenario_atual, $semAtual){
    
    if (!isset($scenarios_vector)){
        
        $scenarios_vector = array();
        
    }
    
    if ($semAtual){
        
        $queryCenarios = "SELECT id_cenario, titulo    
							FROM cenario    
							WHERE id_projeto = '$id_project' AND id_cenario <> '$id_cenario_atual' 
							ORDER BY titulo DESC";
    } else{
        
        $queryCenarios = "SELECT id_cenario, titulo    
							FROM cenario    
							WHERE id_projeto = '$id_project' 
							ORDER BY titulo DESC";
        
    }

    $resultadoQueryCenarios = mysql_query($queryCenarios) or die("Erro ao enviar a query de selecao !!" . mysql_error());

    $i = 0;
    
    while ($linhaCenario = mysql_fetch_object($resultadoQueryCenarios)){
        
        $scenarios_vector[$i] = $linhaCenario;
        $i++;
        
    }

    return $scenarios_vector;
}

// Divides the array in two
function divide_array(&$vector, $ini, $fim, $type){
    
    $i = $ini;
    $j = $fim;
    $dir = 1;

    while ($i < $j){
        
        if (strcasecmp($type, 'cenario') == 0){
            
            if (strlen($vector[$i]->titulo) < strlen($vector[$j]->titulo)) {
                
                $str_temp = $vector[$i];
                $vector[$i] = $vector[$j];
                $vector[$j] = $str_temp;
                $dir--;
                
            }
            
        }else{
            
            if (strlen($vector[$i]->nome) < strlen($vector[$j]->nome)){
                
                $str_temp = $vector[$i];
                $vector[$i] = $vector[$j];
                $vector[$j] = $str_temp;
                $dir--;
                
            }
        }
        
        if ($dir == 1){
           $j--; 
        }else{
           $i++; 
        }
            
    }

    return $i;
}

// Sort the vector

function quicksort(&$vector, $ini, $fim, $type){
    
    if ($ini < $fim){
        
        $k = divide_array($vector, $ini, $fim, $type);
        
        quicksort($vector, $ini, $k - 1, $type);
        quicksort($vector, $k + 1, $fim, $type);
        
    }
}

// Function that construct the links according to the text, passed through parameters $text, $lexicon passed through
// the parameters $lexicon_vector, and scenario passed through the parameter $scenario_vector   

function monta_links($text, $lexicon_vector, $scenarios_vector){
    
    $copy_text = $text;
    
    if (!isset($aux_lexicon_vector)){
        
        $aux_lexicon_vector = array();
    }
    
    if (!isset($vetorAuxCenarios)){
        
        $vetorAuxCenarios = array();
        
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
            
            $nomeLexico = escapa_metacaracteres($lexicon_vector[$i]->nome);
            $regex = "/(\s|\b)(" . $nomeLexico . ")(\s|\b)/i";
            
            if (preg_match($regex, $copy_text) != 0){
                
                $copy_text = preg_replace($regex, " ", $copy_text);
                $aux_lexicon_vector[$a] = $lexicon_vector[$i];
                
                $a++;
                
            }
            $i++;
            
        }
    } else{
        

        // If the scenario vector isnt empty, it will look for lexicons and scenarios

        $tamLexicos = count($lexicon_vector);
        $tamCenarios = count($scenarios_vector);
        $tamanhoTotal = $tamLexicos + $tamCenarios;
        
        $i = 0;
        $j = 0;
        $a = 0;
        $b = 0;
        $contador = 0;
        
        while ($contador < $tamanhoTotal){
            
            if (($i < $tamLexicos ) && ($j < $tamCenarios)){
                
                if (strlen($scenarios_vector[$j]->titulo) < strlen($lexicon_vector[$i]->nome)){
                    
                    $nomeLexico = escapa_metacaracteres($lexicon_vector[$i]->nome);
                    $regex = "/(\s|\b)(" . $nomeLexico . ")(\s|\b)/i";
                    
                    if (preg_match($regex, $copy_text) != 0){
                        
                        $copy_text = preg_replace($regex, " ", $copy_text);
                        $aux_lexicon_vector[$a] = $lexicon_vector[$i];
                        $a++;
                        
                    }
                    
                    $i++;
                    
                }else{

                    $tituloCenario = escapa_metacaracteres($scenarios_vector[$j]->titulo);
                    $regex = "/(\s|\b)(" . $tituloCenario . ")(\s|\b)/i";
                    
                    if (preg_match($regex, $copy_text) != 0){
                        
                        $copy_text = preg_replace($regex, " ", $copy_text);
                        $vetorAuxCenarios[$b] = $scenarios_vector[$j];
                        $b++;
                        
                    }
                    
                    $j++;
                }
                
            }else if($tamLexicos == $i){

                $tituloCenario = escapa_metacaracteres($scenarios_vector[$j]->titulo);
                $regex = "/(\s|\b)(" . $tituloCenario . ")(\s|\b)/i";
                
                if (preg_match($regex, $copy_text) != 0){
                    
                    $copy_text = preg_replace($regex, " ", $copy_text);
                    $vetorAuxCenarios[$b] = $scenarios_vector[$j];
                    $b++;
                    
                }
                
                $j++;
                
            }else if($tamCenarios == $j){

                $nomeLexico = escapa_metacaracteres($lexicon_vector[$i]->nome);
                $regex = "/(\s|\b)(" . $nomeLexico . ")(\s|\b)/i";
                
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
        
        $nomeLexico = escapa_metacaracteres($aux_lexicon_vector[$index]->nome);
        $regex = "/(\s|\b)(" . $nomeLexico . ")(\s|\b)/i";
        $link = "<a title=\"L�xico\" href=\"main.php?t=l&id=" . $aux_lexicon_vector[$index]->id_lexico . "\">" . $aux_lexicon_vector[$index]->nome . "</a>";
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
    
    while ($index < count($vetorAuxCenarios)){
        
        $tituloCenario = escapa_metacaracteres($vetorAuxCenarios[$index]->titulo);
        $regex = "/(\s|\b)(" . $tituloCenario . ")(\s|\b)/i";
        $link = "$1<a title=\"Cen�rio\" href=\"main.php?t=c&id=" . $vetorAuxCenarios[$index]->id_cenario . "\"><span style=\"font-variant: small-caps\">" . $vetorAuxCenarios[$index]->titulo . "</span></a>$3";
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

