<?php 

    //session_start(); 
    require_once '/Functions/check_User.php';
    //include("funcoes_genericas.php"); 

    check_User("index.php");        // Cenario: controle de acesso 

// frame_inferior.php 
// Dada a base, o tipo "c", "l", "oc", "or" e "oa" e o 
// id do respectivo, mostra os dados necessï¿½rios 
// no frame. 

    function frame_inferior( $db, $tipo, $id  ) 
    { 
        $search = "'<[\/\!]*?[^<>]*?>'si"; 
        $replace = ""; 


        if ( $tipo == "c" )            // Se for scenario 
        { 
            // Seleciona os cenï¿½rios que referenciam o cenï¿½rio 
            // com o id passado. 
            $query_select_scenario = "SELECT id_scenario, title 
                            FROM   scenario, scenario_to_scenario 
                            WHERE  id_scenario = id_scenario_from 
                            AND    id_scenario_to = " . $id ; 

            $tb_cenario = mysql_query( $query_select_scenario ) or 
                          die( "Erro ao enviar a query de selecao." ) ; 
?> 

            <table> 
            <tr> 
              <th>Cenários</th> 
            </tr> 

<?php 
            while ( $row = mysql_fetch_row( $tb_cenario ) ) 
            { 
                // Retira as tags HTML de dentro do title do scenario 
                $row[1] = preg_replace($search, $replace, $row[1]); 
                $link = "<a href=javascript:reCarrega" . 
                        "('main.php?id=$row[0]&t=c');><span style=\"font-variant: small-caps\">$row[1]</span></a>" ; 
?> 

                <td><?=$link?></td> 

<?php 
            } // while 
        } // if 

        else if ( $tipo == "l" ) 
        { 
            // Seleciona os cenï¿½rios que referenciam o lï¿½xico 
            // com o id passado. 
            $query_select_scenario = "SELECT c.id_scenario, c.title 
                            FROM   scenario c, scenario_to_lexicon cl 
                            WHERE  c.id_scenario = cl.id_scenario 
                            AND    cl.id_lexicon = " . $id ; 

            $tb_cenario = mysql_query( $query_select_scenario ) or 
                          die( "Erro ao enviar a query de selecao." ) ; 

            // Seleciona os lexicos que referenciam o lexicon 
            // com o id passado. 
            $qry_lexico = "SELECT id_lexicon, name 
                           FROM   lexicon, lexicon_to_lexicon 
                           WHERE  id_lexicon  = id_lexicon_from 
                           AND    id_lexicon_to = " . $id ; 

            $tb_lexico = mysql_query( $qry_lexico ) or 
                         die( "Erro ao enviar a query de selecao." ) ; 
?> 

            <table> 
            <tr> 
                <th>Cenï¿½rios</th> 
                <th>Lï¿½xicos</th> 
            </tr> 

<?php 
            while ( 1 ) 
            { 
?> 

                <tr> 

<?php 
                if ( $rowc = mysql_fetch_row( $tb_cenario ) ) 
                { 
                    $rowc[1] = preg_replace($search, $replace, $rowc[1]); 
                    $link = "<a href=javascript:reCarrega" . 
                            "('main.php?id=$rowc[0]&t=c');><span style=\"font-variant: small-caps\">$rowc[1]</span></a>" ; 
                } // if 
                else 
                { 
                    $link = "" ; 
                } // else 
?> 

                <td><?=$link?></td> 

<?php 
                if ( $rowl = mysql_fetch_row( $tb_lexico ) ) 
                { 
                    $link = "<a href=javascript:reCarrega" . 
                            "('main.php?id=$rowl[0]&t=l');>$rowl[1]</a>" ; 
                } // if 
                else 
                { 
                    $link = "" ; 
                } // else 
?> 

                <td><?=$link?></td> 
</td>                </tr> 

<?php 
                if ( !( $rowc ) && !( $rowl ) ) 
                { 
                    break ; 
                } // if 
            } // while 

        } //elseif 

        else if ( $tipo == "oc" ) /* CONCEITO */ 
        { 
           $query_select = "SELECT   r.id_relation, r.name, predicate 
                 FROM     concept c, relation_concept rc, relation r 
                 WHERE    c.id_concept = $id 
                 AND      c.id_concept = rc.id_concept 
                 AND      r.id_relation = rc.id_relation 
                 ORDER BY r.name  ";  
           $result = mysql_query($query_select) or die("Erro ao enviar a query de selecao !!". mysql_error());  
        
           print "<TABLE><tr><th align=left CLASS=\"Estilo\">Relaï¿½ï¿½o</th><th align=left CLASS=\"Estilo\">Conceito</Th></tr>"; 

           while ($line = mysql_fetch_array($result, MYSQL_BOTH))   
           { 
              print "<tr><td CLASS=\"Estilo\"><a href=\"main.php?id=$line[0]&t=or\">$line[1]</a></td><td>$line[2]</TD></tr>"; 
           } 
            


        } //elseif 

        else if ( $tipo == "or" ) /* RELAï¿½ï¿½O */ 
        { 
           $query_select = "SELECT DISTINCT  c.id_concept, c.name 
                 FROM     concept c, relation_concept rc, relation r 
                 WHERE    r.id_relation = $id 
                 AND      c.id_concept = rc.id_concept 
                 AND      r.id_relation = rc.id_relation 
                 ORDER BY r.name  ";  
           $result = mysql_query($query_select) or die("Erro ao enviar a query de selecao !!". mysql_error());  
        
           print "<TABLE><tr><th align=left CLASS=\"Estilo\">Conceitos</th></tr>"; 

           while ($line = mysql_fetch_array($result, MYSQL_BOTH))   
           { 
              print "<tr><td CLASS=\"Estilo\"><a href=\"main.php?id=$line[0]&t=oc\">$line[1]</a></td></tr>"; 
           } 
            


        } //elseif 

        else if ( $tipo == "oa" ) /* AXIOMA */ 
        { 

           $query_select = "SELECT   * 
                 FROM     axiom
                 WHERE    id_axiom = \"$id\";";  

           $result = mysql_query($query_select) or die("Erro ao enviar a query de selecao !!". mysql_error());  
        
           print "<TABLE><tr><th align=left CLASS=\"Estilo\">Conceito</th><th align=left CLASS=\"Estilo\">Conceito disjunto</th></tr>"; 

           while ($line = mysql_fetch_array($result, MYSQL_BOTH))   
           { 
              $axi = explode(" disjoint ", $line[1]);     

              $query_select_concept = "SELECT * FROM concept WHERE name = \"$axi[0]\";";          
              $result1 = mysql_query($query_select_concept) or die("Erro ao enviar a query de selecao !!". mysql_error());  
              $line1 = mysql_fetch_array($result1, MYSQL_BOTH) ; 
              print "<tr><td CLASS=\"Estilo\"><a href=\"main.php?id=$line1[0]&t=oc\">$axi[0]</a></td>";

              $q2 = "SELECT * FROM concept WHERE name = \"$axi[1]\";";          
              $result2 = mysql_query($q2) or die("Erro ao enviar a query de selecao !!". mysql_error());  
              $line2 = mysql_fetch_array($result2, MYSQL_BOTH) ; 
              print "<td CLASS=\"Estilo\"><a href=\"main.php?id=$line2[0]&t=oc\">$axi[1]</a></td></tr>"; 
           } 
            


        } //elseif 
        
?> 

</table> 

<?php 
    } // procura_ref 
?>
