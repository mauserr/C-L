<?php

###################################################################
# Funcao faz um insert na tabela de pedido.
# Para remover um conceito ela deve receber
# o id do conceito e id projeto.(1.1)
# Ao final ela manda um e-mail para o gerente do projeto
# referente a este conceito.(2.1)
# Arquivos que utilizam essa funcao:
# rmv_conceito.php
###################################################################
if (!(function_exists("insert_request_remove_concept"))) {
    function insert_request_remove_concept($id_project,$id_concept,$id_user){

        assert($id_project!=NULL);
        assert($id_concept!=NULL);
        assert($id_user!=NULL);
        
        $DB = new PGDB () ;
        $insere = new QUERY ($DB) ;
        $select = new QUERY ($DB) ;
        $select2 = new QUERY ($DB) ;
        $select->execute("SELECT * FROM concept WHERE id_concept = $id_concept") ;
        $concept = $select->gofirst ();
        $name = $concept['name'] ;
        
        $insere->execute("INSERT INTO request_concept(id_project,id_concept,name,id_user,type_request,aprove) VALUES ($id_project,$id_concept,'$name',$id_user,'remove',0)") ;
        $select->execute("SELECT * FROM user WHERE id_user = $id_user") ;
        $select2->execute("SELECT * FROM participates WHERE manager = 1 and id_project = $id_project") ;
        
        if ($select->getntuples() == 0&&$select2->getntuples() == 0){
            echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
        }else{
            $record = $select->gofirst ();
            $name = $record['name'] ;
            $email = $record['email'] ;
            $record2 = $select2->gofirst ();
            while($record2 != 'LAST_RECORD_REACHED'){
                $id = $record2['id_user'] ;
                $select->execute("SELECT * FROM user WHERE id_user = $id") ;
                $record = $select->gofirst ();
                $mail_manager = $record['email'] ;
                mail("$mail_maneger", "Pedido de Remover Conceito", "O usuario do sistema $name2\nPede para remover o conceito $id_concept \nObrigado!","From: $name\r\n"."Reply-To: $email\r\n");
                $record2 = $select2->gonext();
            }
        }
    }
}

###################################################################
# Processa um pedido identificado pelo seu id.
# Recebe o id do pedido.(1.1)
# Faz um select para pegar o pedido usando o id recebido.(1.2)
# Pega o campo tipo_pedido.(1.3)
# Se for para remover: Chamamos a funcao remove();(1.4)
# Se for para alterar: Devemos (re)mover o cenario e inserir o novo.
# Se for para inserir: chamamos a funcao insert();
###################################################################
if (!(function_exists("treat_concept_request"))) {
    function treat_concept_request($id_request){
        assertNotNull($id_request);
        
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $delete = new QUERY ($DB) ;
        $select->execute("SELECT * FROM request_concept WHERE id_request = $id_request") ;
        if ($select->getntuples() == 0){
            echo "<BR> [ERRO]Pedido invalido." ;
        }else{
            $record = $select->gofirst () ;
            $type_request = $record['type_request'] ;
            if(!strcasecmp($type_request,'remove')){
                $id_concept = $record['id_concept'] ;
                $id_project = $record['id_project'] ;
                remove_concept($id_project,$id_concept) ;
            }else{
                
                $id_project = $record['id_project'] ;
                $name = $record['name'] ;
                $description = $record['description'] ;
                $namespace = $record['namespace'] ;
                
                if(!strcasecmp($type_request,'alter')){
                    $id_scenario = $record['id_concept'] ;
                    remove_concept($id_project,$id_concept) ;
                }
                add_concept($id_project, $name, $description, $namespace) ;
            }
        }
    }
}

###################################################################
# Essa funcao recebe um id de conceito e remove todos os seus
# links e relacionamentos existentes.
###################################################################
if (!(function_exists("remove_concept"))) {
    function remove_concept($id_project, $id_concept){
        assertNotNull($id_project, $id_concept);
        
        $DB = new PGDB () ;
        $sql = new QUERY ($DB) ;
        $sql2 = new QUERY ($DB) ;
        $sql3 = new QUERY ($DB) ;
        $sql4 = new QUERY ($DB) ;
        $sql5 = new QUERY ($DB) ;
        $sql6 = new QUERY ($DB) ;
        $sql7 = new QUERY ($DB) ;
        # Este select procura o cenario a ser removido
        # dentro do projeto
        
        $sql2->execute ("SELECT * FROM concept WHERE id_project = $id_project and id_concept = $id_concept") ;
        if ($sql2->getntuples() == 0){
            //echo "<BR> Cenario nao existe para esse projeto." ;
        }else{
            $record = $sql2->gofirst ();
            $name_concept = $record['name'] ;
            # tituloCenario = Nome do cenario com id = $id_cenario
        }
        # [ATENCAO] Essa query pode ser melhorada com um join
        //print("<br>SELECT * FROM cenario WHERE id_projeto = $id_projeto");
        /*  $sql->execute ("SELECT * FROM cenario WHERE id_projeto = $id_projeto AND id_cenario != $tituloCenario");
        if ($sql->getntuples() == 0){
        echo "<BR> Projeto n�o possui cenarios." ;
        }else{*/
        $query_sql = "SELECT * FROM concept WHERE id_project = $id_project AND id_concept != $id_concept";
        //echo($qr)."          ";
        $query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query de SELECT<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        while ($result = mysql_fetch_array($query_result_sql)){
            
            # Percorre todos os cenarios tirando as tag do conceito
            # a ser removido
            //$record = $sql->gofirst ();
            //while($record !='LAST_RECORD_REACHED'){
            $id_concept_ref = $result['id_concept'] ;
            $previous_name = $result['name'] ;
            $previous_description = $result['description'] ;
            $previous_namespace = $result['namespace'] ;
            #echo        "/<a title=\"Cen�rio\" href=\"main.php?t='c'&id=$id_cenario>($tituloCenario)<\/a>/mi"  ;
            #$episodiosAnterior = "<a title=\"Cen�rio\" href=\"main.php?t=c&id=38\">robin</a>" ;
            /*"'<a title=\"Cen�rio\" href=\"main.php?t=c&id=38\">robin<\/a>'si" ; */
            $tiratag = "'<[\/\!]*?[^<>]*?>'si" ;
            //$tiratagreplace = "";
            //$tituloCenario = preg_replace($tiratag,$tiratagreplace,$tituloCenario);
            $regexp = "/<a[^>]*?>($name_concept)<\/a>/mi" ;//rever
            $replace = "$1";
            //echo($episodiosAnterior)."   ";
            //$tituloAtual = $tituloAnterior ;
            //*$tituloAtual = preg_replace($regexp,$replace,$tituloAnterior);*/
            $current_description = preg_replace($regexp,$replace,$previous_description);
            $current_namespace = preg_replace($regexp,$replace,$previous_namespace);
            /*echo "ant:".$episodiosAtual ;
            echo "<br>" ;
            echo "dep:".$episodiosAnterior ;*/
            // echo($tituloCenario)."   ";
            // echo($episodiosAtual)."  ";
            //print ("<br>update cenario set objetivo = '$objetivoAtual',contexto = '$contextoAtual',atores = '$atoresAtual',recursos = '$recursosAtual',episodios = '$episodiosAtual' where id_cenario = $idCenarioRef ");
            $sql7->execute ("update concept set description = '$current_description', namespace = '$current_namespace' where id_concept = $id_concept_ref");
            
            //$record = $sql->gonext() ;
            // }
        }
        # Remove o conceito escolhido
        $sql6->execute ("DELETE FROM concept WHERE id_concept = $id_concept") ;
        $sql6->execute ("DELETE FROM relation_concept WHERE id_concept = $id_concept") ;   
    }   
}

?>
