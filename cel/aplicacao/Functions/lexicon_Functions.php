<?php

require_once '/../bd.inc';
require_once '/../security.php';
require_once '/../bd_class.php';

###################################################################
# Insere um lexico no banco de dados.
# Recebe o id_projeto, nome, no��o, impacto e os sinonimos. (1.1)
# Insere os valores do lexico na tabela LEXICO. (1.2)
# Insere todos os sinonimos na tabela SINONIMO. (1.3)
# Devolve o id_lexico. (1.4)
#
###################################################################


if (!(function_exists("include_lexicon"))){
    
    function include_lexicon($id_project, $name, $notion, $impact, $synonymous, $classification){
        
        assert($id_project != NULL);
	assert($name != NULL);
        assert(is_int($id_project));
        assert(is_string($name));
        assert(is_string($notion));
        assert(is_string($impact));
        assert(is_string($synonymous));
        assert(is_string($classification));
    
        
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $data = date("Y-m-d");
     
                
        $query_sql = "INSERT INTO lexicon (id_project, data, name, notion, impact, type)
              VALUES ($id_project, '$data', '" .data_prepare(strtolower($name)). "',
			  '".data_prepare($notion)."', '".data_prepare($impact)."', '$classification')";
				
		mysql_query($query_sql) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                $affected_rows = mysql_affected_rows();
        //sinonimo
        $newLexId = mysql_insert_id($connect);
        
        
        if( ! is_array($synonymous)){
            $synonymous = array();
        }
        
        foreach($synonymous as $novoSin){
            
       		$query_sql = "INSERT INTO synonym (id_lexicon, name, id_project)
                VALUES ($newLexId, '" . data_prepare(strtolower($novoSin)) . "', $id_project)";
                mysql_query($query_sql, $connect) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        }
        
        $query_sql = "SELECT max(id_lexicon) FROM lexicon";
        $qrr = mysql_query($query_sql) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $result = mysql_fetch_row($qrr);
        if($affected_rows == 0){
            return false;
        }else{
            return $result[0];
        }
    }
}


###################################################################
# Funcao faz um select na tabela lexico.
# Para inserir um novo lexico, deve ser verificado se ele ja existe,
# ou se existe um sinonimo com o mesmo nome.
# Recebe o id do projeto e o nome do lexico (1.0)
# Faz um SELECT na tabela lexico procurando por um nome semelhante
# no projeto (1.1)
# Faz um SELECT na tabela sinonimo procurando por um nome semelhante
# no projeto (1.2)
# retorna true caso nao exista ou false caso exista (1.3)
###################################################################
function checkExistingLexicon($project, $name){
    
	assert(is_string($project, $name));
	assertNotNull($project, $name);


	$doenstexist= false;

	$connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	$query_sql = "SELECT * FROM lexicon WHERE id_project = $project AND name = '$name' ";
	$query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	$resultArray = mysql_fetch_array($query_result_sql);
        
	if ( $resultArray == false ){
		$doenstexist = true;
	}

	$query_sql = "SELECT * FROM synonym WHERE id_project = $project AND name = '$name' ";
	$query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	$resultArray = mysql_fetch_array($qr);

	if ( $resultArray != false ){
		$doenstexist = false;
	}

	return $doenstexist;


}

//
// Para a correta inclusao de um termo no lexico, uma serie de procedimentos
// precisam ser tomados (relativos ao requisito 'navegacao circular'):
//
// 1. Incluir o novo termo na base de dados;
// 2. Para todos os cenarios daquele projeto:
//      2.1. Procurar em titulo, objetivo, contexto, recursos, atores, episodios
//           por ocorrencias do termo incluido ou de seus sinonimos;
//      2.2. Para os campos em que forem encontradas ocorrencias:
//              2.2.1. Incluir entrada na tabela 'centolex';
// 3. Para todos termos do lexico daquele projeto (menos o recem-inserido):
//      3.1. Procurar em nocao, impacto por ocorrencias do termo inserido ou de seus sinonimos;
//      3.2. Para os campos em que forem encontradas ocorrencias:
//              3.2.1. Incluir entrada na tabela 'lextolex';
//      3.3. Procurar em nocao, impacto do termo inserido por
//           ocorrencias de termos do lexico do mesmo projeto;
//      3.4. Se achar alguma ocorrencia:
//              3.4.1. Incluir entrada na table 'lextolex';

if (!(function_exists("adicionar_lexico"))){
    
    function adicionar_lexico($id_project, $name, $notion, $impact, $synonymous, $classification){
        
        assert(is_int($id_project));
        assert(is_string($name, $notion, $impact, $synonymous, $classification));
        assertNotNull($id_project, $name, $notion, $impact, $synonymous, $classification);
        
        
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $id_incluido = include_lexicon($id_project, $name, $notion, $impact, $synonymous, $classification); // (1)
        
        $qr = "SELECT id_scenario, title, objective, context, actors, resources, exception, episodes
              FROM scenario
              WHERE id_project = $id_projeto";
        
        $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)){
            // 2  - Para todos os cenarios
        
           $nomeEscapado = escape_metacharacter($name);
		   $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
         
            if( (preg_match($regex, $result['objetivo']) != 0) ||
                (preg_match($regex, $result['contexto']) != 0) ||
                (preg_match($regex, $result['atores']) != 0)   ||
                (preg_match($regex, $result['recursos']) != 0) ||
                (preg_match($regex, $result['excecao']) != 0)  ||
                (preg_match($regex, $result['episodios']) != 0) ){
                
            //2.2
        
                $q = "INSERT INTO centolex (id_cenario, id_lexico)
                     VALUES (" . $result['id_cenario'] . ", $id_incluido)"; //2.2.1
        
                mysql_query($q) or die("Erro ao enviar a query de INSERT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
          
            }
        }

        
        //sinonimos do novo lexico
        $count = count($synonymous);
        for ($i = 0; $i < $count; $i++){
            
            $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
            while ($result2 = mysql_fetch_array($qrr)){
                
                $nomeSinonimoEscapado = escape_metacharacter( $synonymous[$i] );
				$regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                if( (preg_match($regex, $result2['objetivo']) != 0) ||
                    (preg_match($regex, $result2['contexto']) != 0) ||
                    (preg_match($regex, $result2['atores']) != 0)   ||
                    (preg_match($regex, $result2['recursos']) != 0) ||
                    (preg_match($regex, $result2['excecao']) != 0)  ||
                    (preg_match($regex, $result2['episodios']) != 0) ){ 
                    
                            
                    $qLex = "SELECT * FROM centolex WHERE id_cenario = " . $result2['id_cenario'] . " AND id_lexico = $id_incluido ";
                    $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    $resultArraylex = mysql_fetch_array($qrLex);
                
                    if ( $resultArraylex == false ){
                    
                        $q = "INSERT INTO centolex (id_cenario, id_lexico)
                             VALUES (" . $result2['id_cenario'] . ", $id_incluido)";                   
                    
                        mysql_query($q) or die("Erro ao enviar a query de INSERT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    } //if
                }//if                
            }//while            
        } //for
        
        
        $qlo = "SELECT id_lexicon, name, notion, impact, type
               FROM lexicon
               WHERE id_project = $id_projeto
               AND id_lexicon != $id_incluido";
                     
        //pega todos os outros lexicos
        $qrr = mysql_query($qlo) or die("Erro ao enviar a query de SELECT no LEXICO<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)){
            // (3)
        
            $nomeEscapado = escape_metacharacter($name);
            $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
            
            if ( (preg_match($regex, $result['nocao']) != 0 ) ||
                 (preg_match($regex, $result['impacto'])!= 0) ) {
                
                
                $qLex = "SELECT * FROM lextolex WHERE id_lexico_from = " . $result['id_lexico'] . " AND id_lexico_to = $id_incluido";
                $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                $resultArraylex = mysql_fetch_array($qrLex);
      
                if ( $resultArraylex == false ){
                    
                    $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
                          VALUES (" . $result['id_lexico'] . ", $id_incluido)";
                    
                    mysql_query($q) or die("Erro ao enviar a query de INSERT no lextolex 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                }
            }
         
			$nomeEscapado = escape_metacharacter( $result['nome'] );
            $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
         
            if((preg_match($regex, $notion) != 0) ||
               (preg_match($regex, $impact) != 0) ){
                // (3.3)        
        
                $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to) VALUES ($id_incluido, " . $result['id_lexico'] . ")"; 
        
                mysql_query($q) or die("Erro ao enviar a query de insert no centocen<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
            }
       
        }   // while
        
        
        //lexico para lexico
        
        $ql = "SELECT id_lexicon, name, notion, impact
              FROM lexicon
              WHERE id_project = $id_projeto
              AND id_lexicon != $id_incluido";                                                                     
        
        //sinonimos dos outros lexicos no texto do inserido
        
        $qrr = mysql_query($ql) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $count = count($synonymous);
        for ($i = 0; $i < $count; $i++){
            
            while ($resultl = mysql_fetch_array($qrr)){
                               
                           $nomeSinonimoEscapado = escape_metacharacter( $synonymous[$i] );
			   $regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                if ( (preg_match($regex, $resultl['nocao']) != 0)  ||
                     (preg_match($regex, $resultl['impacto']) != 0)){
                    
                                    
                    $qLex = "SELECT * FROM lextolex WHERE id_lexico_from = " . $resultl['id_lexico'] . " AND id_lexico_to = $id_incluido";
                    $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    $resultArraylex = mysql_fetch_array($qrLex);
                    
                    if ( $resultArraylex == false ){
                        
                        $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
                         VALUES (" . $resultl['id_lexico'] . ", $id_incluido)";            
                        
                        mysql_query($q) or die("Erro ao enviar a query de insert no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    }//if
                }    //if
            }//while
        }//for
        
        //sinonimos ja existentes
        
        $qSinonimos = "SELECT name, id_lexicon FROM synonym WHERE id_project = $id_projeto AND id_lexicon != $id_incluido AND id_pedidolex = 0";
        
        $qrrSinonimos = mysql_query($qSinonimos) or die("Erro ao enviar a query de select no sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $nomesSinonimos = array();
        
        $id_lexicoSinonimo = array();
        
        while($rowSinonimo = mysql_fetch_array($qrrSinonimos)){
            
            $nomesSinonimos[]     = $rowSinonimo["nome"];
            $id_lexicoSinonimo[]  = $rowSinonimo["id_lexico"];
            
        }
      
    }
}



###################################################################
# Funcao faz um insert na tabela de pedido.
# Para inserir um novo lexico ela deve receber os campos do novo
# lexicos.
# Ao final ela manda um e-mail para o gerente do projeto
# referente a este lexico caso o criador n�o seja o gerente.
# Arquivos que utilizam essa funcao:
# add_lexico.php
###################################################################
if (!(function_exists("inserirPedidoAdicionarLexico"))){
    
    function inserirPedidoAdicionarLexico($id_project,$name,$notion,$impact,$id_user,$synonymous, $classification){
        assert(is_int($id_project, $id_user));
        assert(is_string($name, $notion, $impact, $synonymous, $classification));
        assertNotNull($id_project, $name, $notion, $impact,$id_user, $synonymous, $classification);
        
        
        $DB = new PGDB() ;
        $insere = new QUERY($DB) ;
        $select = new QUERY($DB) ;
        $select2 = new QUERY($DB) ;
        
        $q = "SELECT * FROM participa WHERE gerente = 1 AND id_usuario = $id_user AND id_projeto = $id_project";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        
        
        if ( $resultArray == false){
            
            $insere->execute("INSERT INTO pedidolex (id_projeto,nome,nocao,impacto,tipo,id_usuario,tipo_pedido,aprovado) VALUES ($id_project,'$name','$notion','$impact','$classification',$id_user,'inserir',0)") ;
            
            $newId = $insere->getLastId();
            
            $select->execute("SELECT * FROM usuario WHERE id_usuario = '$id_user'") ;
            
            $select2->execute("SELECT * FROM participa WHERE gerente = 1 and id_projeto = $id_project") ;
            
            
            //insere sinonimos
            
            foreach($synonymous as $sin){
                
                $insere->execute("INSERT INTO sinonimo (id_pedidolex, nome, id_projeto) 
		VALUES ($newId, '".data_prepare(strtolower($sin))."', $id_project)");
            }
            //fim da insercao dos sinonimos
            
            if ($select->getntuples() == 0 &&$select2->getntuples() == 0){
                echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
            }else{
                
                $record = $select->gofirst ();
                $name2 = $record['nome'] ;
                $email = $record['email'] ;
                $record2 = $select2->gofirst ();
                
                while($record2 != 'LAST_RECORD_REACHED'){
                    $id = $record2['id_usuario'] ;
                    $select->execute("SELECT * FROM usuario WHERE id_usuario = $id") ;
                    $record = $select->gofirst ();
                    $mailGerente = $record['email'] ;
                    mail("$mailGerente", "Pedido de Inclus�o de L�xico", "O usuario do sistema $name2\nPede para inserir o lexico $name \nObrigado!","From: $name2\r\n"."Reply-To: $email\r\n");
                    $record2 = $select2->gonext();
                    
                    
                }
            }
            
        }else{ //Eh gerente
        	adicionar_lexico($id_project, $name, $notion, $impact, $synonymous, $classification) ;
        
        }
    }
}


###################################################################
# Essa funcao recebe um id de lexico e remove todos os seus
# links e relacionamentos existentes em todas as tabelas do banco.
###################################################################
if (!(function_exists("removeLexico"))) {
    function removeLexico($id_project,$id_lexicon, $lexicon_name){
        
        assert($id_project != NULL);
	assert($id_project > 0);
	assert($id_lexicon != NULL);
	assert($id_lexicon > 0);
	assert($id_project != NULL);
	assert($id_project > 0);
	assert($lexicon_name != NULL);
        assert(is_int($id_lexicon, $id_project));
        assert(is_string($lexicon_name));
		   
        $DB = new PGDB() ;
        $delete = new QUERY ($DB) ;      
        
        if($lexicon_name != null){
            bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
            $query = "SELECT id_lexicon FROM lexicon where name = '$lexicon_name'";
            $query_result = mysql_query($query);
            $id_lexicon = mysql_result($query_result, 0, "id_lexicon");
        }   
        
        # Remove o relacionamento entre o lexico a ser removido
        # e outros lexicos que o referenciam
        $delete->execute ("DELETE FROM lexicon_to_lexicon WHERE id_lexicon_from = $id_lexicon");
        $delete->execute ("DELETE FROM lexicon_to_lexicon WHERE id_lexicon_to = $id_lexicon");
        $delete->execute ("DELETE FROM scenario_to_lexicon WHERE id_lexicon = $id_lexicon");
        
        # Remove o lexico escolhido
        $delete->execute ("DELETE FROM synonym WHERE id_lexicon = $id_lexicon");
        $delete->execute ("DELETE FROM lexicon WHERE id_lexicon = $id_lexicon");
        $affected_rows = mysql_affected_rows();
        
        if(affected_rows == 0){
            return false;
        }else{
            return true;
        }
    }
}

###################################################################
# Funcao faz um insert na tabela de pedido.
# Para remover um lexico ela deve receber
# o id do lexico e id projeto.(1.1)
# Ao final ela manda um e-mail para o gerente do projeto
# referente a este lexico.(2.1)
# Arquivos que utilizam essa funcao:
# rmv_lexico.php
###################################################################
if (!(function_exists("inserirPedidoRemoverLexico"))) {
    function insertRequestRemoveLexicon($id_project,$id_lexicon,$id_user){
        assert(is_int($id_project, $id_lexicon, $id_user));
        assertNotNull($id_lexicon, $id_project, $id_user);
        
        
        
        $DB = new PGDB () ;
        $insere = new QUERY ($DB) ;
        $select = new QUERY ($DB) ;
        $select2 = new QUERY ($DB) ;
        
        $q = "SELECT * FROM participa WHERE gerente = 1 AND id_usuario = $id_usuario AND id_projeto = $id_projeto";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        
        if ( $resultArray == false ){ //nao e gerente
        
        
	        $select->execute("SELECT * FROM lexico WHERE id_lexico = $id_lexicon") ;
	        $lexicon = $select->gofirst ();
	        $name = $lexicon['nome'] ;
	        
	        $insere->execute("INSERT INTO pedidolex (id_projeto,id_lexico,nome,id_usuario,tipo_pedido,aprovado) VALUES ($id_project,$id_lexicon,'$name',$id_user,'remover',0)") ;
	        $select->execute("SELECT * FROM usuario WHERE id_usuario = $id_user") ;
	        $select2->execute("SELECT * FROM participa WHERE gerente = 1 and id_projeto = $id_project") ;
	        
	        if ($select->getntuples() == 0&&$select2->getntuples() == 0){
	            echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
	        }else{
	            $record = $select->gofirst ();
	            $name = $record['nome'] ;
	            $email = $record['email'] ;
	            $record2 = $select2->gofirst ();
                    
	            while($record2 != 'LAST_RECORD_REACHED'){
	                $id = $record2['id_usuario'] ;
	                $select->execute("SELECT * FROM usuario WHERE id_usuario = $id") ;
	                $record = $select->gofirst ();
	                $mailGerente = $record['email'] ;
	                mail("$mailGerente", "Pedido de Remover L�xico", "O usuario do sistema $name2\nPede para remover o lexico $id_lexicon \nObrigado!","From: $name\r\n"."Reply-To: $email\r\n");
	                $record2 = $select2->gonext();
	            }
	        }
        }else{ // e gerente
        	removeLexico($id_project,$id_lexicon, null);
        }
    }
}

###################################################################
# Processa um pedido identificado pelo seu id.
# Recebe o id do pedido.(1.1)
# Faz um select para pegar o pedido usando o id recebido.(1.2)
# Pega o campo tipo_pedido.(1.3)
# Se for para remover: Chamamos a funcao remove();(1.4)
# Se for para alterar: Devemos (re)mover o lexico e inserir o novo.
# Se for para inserir: chamamos a funcao insert();
###################################################################
if (!(function_exists("tratarPedidoLexico"))){
    
    function tratarPedidoLexico($id_request){
        assert(is_int($id_request));
        assertNotNull($id_request);
        
        
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $delete = new QUERY ($DB);
        $selectSin = new QUERY ($DB);
        $select->execute("SELECT * FROM pedidolex WHERE id_pedido = $id_request") ;
        
        if ($select->getntuples() == 0){
            echo "<BR> [ERRO]Pedido invalido." ;
        }else{
            $record = $select->gofirst () ;
            $type_request = $record['tipo_pedido'] ;
            
            if(!strcasecmp($type_request,'remover')){
                $id_lexicon = $record['id_lexico'] ;
                $id_project = $record['id_projeto'] ;
                removeLexico($id_project,$id_lexicon, null) ;
                
            }else{
                $id_project = $record['id_projeto'] ;
                $name = $record['nome'] ;
                $notion = $record['nocao'] ;
                $impact = $record['impacto'] ;
                $classification = $record['tipo'];
                
                //synonymous
                
                $synonymous = array();
                $selectSin->execute("SELECT nome FROM sinonimo WHERE id_pedidolex = $id_request");
                $synonymous = $selectSin->gofirst();
                
                if ($selectSin->getntuples() != 0){
               	    while($synonymous != 'LAST_RECORD_REACHED'){
                        
                        $synonymous[] = $synonymous["nome"];
                        $synonymous = $selectSin->gonext();
                    }
                }
                
                if(!strcasecmp($type_request,'alterar')){
                    
                    $id_lexicon = $record['id_lexico'] ;
                    alteraLexico($id_project, $id_lexicon, $name, $notion, $impact, $synonymous, $classification);
                }else if(($idLexicoConflitante = adicionar_lexico($id_project, $name, $notion, $impact, $synonymous, $classification)) <= 0){
                    
                    $idLexicoConflitante = -1 * $idLexicoConflitante;
                    
                    $selectLexConflitante->execute("SELECT nome FROM lexico WHERE id_lexico = " . $idLexicoConflitante);
                    
                    $row = $selectLexConflitante->gofirst();
                    
                    return $row["nome"];
                }
            }
            return null;
            
        }
    }
}

###################################################################
# Funcao faz um insert na tabela de pedido.
# Para alterar um lexico ela deve receber os campos do lexicos
# jah modificados.(1.1)
# Ao final ela manda um e-mail para o gerente do projeto
# referente a este lexico caso o criador n�o seja o gerente.(2.1)
# Arquivos que utilizam essa funcao:
# alt_lexico.php
###################################################################
if (!(function_exists("insertRequestAlterLexicon"))){
    
	function insertRequestAlterLexicon($id_project,$id_lexicon,$name,$notion,$impact,$justification,$id_user, $synonym, $classification){
		assert(is_int($id_lexicon, $id_project, $id_user));
		assert(is_string($name,$notion,$impact,$justificative,$synonym, $classification));
		assertNotNull($id_project,$id_lexicon,$name,$notion,$impact,$justification,$id_user, $synonym, $classification);

		$DB = new PGDB () ;
		$insere = new QUERY ($DB) ;
		$select = new QUERY ($DB) ;
		$select2 = new QUERY ($DB) ;

		$q = "SELECT * FROM participates WHERE manager = 1 AND id_user = $id_user AND id_project = $id_project";
		$qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		$resultArray = mysql_fetch_array($qr);


		if ( $resultArray == false ){ //nao e gerente
		
			$insere->execute("INSERT INTO request_lexicon(id_project,id_lexicon,name,notion,impact,id_user,type_request,aproved,justification, type) VALUES ($id_project,$id_lexico,'$name','$notion','$impact',$id_user,'alter',0,'$justification', '$classification')") ;

			$newPedidoId = $insere->getLastId();

			//sinonimos
			foreach($synonym as $sin){
                            
				$insere->execute("INSERT INTO synonym (id_request_lexicon,name,id_project)
						VALUES ($newPedidoId,'".data_prepare(strtolower($sin))."', $id_project)") ;
			}


			$select->execute("SELECT * FROM user WHERE id_user = '$id_user'") ;
			$select2->execute("SELECT * FROM participates WHERE manager = 1 and id_project = $id_project") ;

			if ($select->getntuples() == 0 && $select2->getntuples() == 0){
				echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
			}else{
				$record = $select->gofirst ();
				$nome2 = $record['name'] ;
				$email = $record['email'] ;
				$record2 = $select2->gofirst ();
                                
				while($record2 != 'LAST_RECORD_REACHED'){
					$id = $record2['id_user'] ;
					$select->execute("SELECT * FROM user WHERE id_user = $id") ;
					$record = $select->gofirst ();
					$mailGerente = $record['email'] ;
					mail("$mailGerente", "Pedido de Alterar L�xico", "O usuario do sistema $name2\nPede para alterar o lexico $name \nObrigado!","From: $name2\r\n"."Reply-To: $email\r\n");
					$record2 = $select2->gonext();
				}
			}
		}
		else{ //� gerente
			alteraLexico($id_project,$id_lexicon, $name, $notion, $impact, $synonym, $classification) ;
		}

	}
}
?>


