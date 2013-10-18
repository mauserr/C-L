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


if (!(function_exists("inclui_lexico"))) 
{
    function inclui_lexico($id_projeto, $nome, $nocao, $impacto, $sinonimos, $classificacao)
    {
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $data = date("Y-m-d");
     
                
        $q = "INSERT INTO lexicon (id_project, data, name, notion, impact, type)
              VALUES ($id_projeto, '$data', '" .data_prepare(strtolower($nome)). "',
			  '".data_prepare($nocao)."', '".data_prepare($impacto)."', '$classificacao')";
				
		mysql_query($q) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                $linhas_afetadas = mysql_affected_rows();
        //sinonimo
        $newLexId = mysql_insert_id($connect);
        
        
        if( ! is_array($sinonimos) )
        $sinonimos = array();
        
        foreach($sinonimos as $novoSin)
        {
       		$q = "INSERT INTO synonym (id_lexicon, name, id_project)
                VALUES ($newLexId, '" . data_prepare(strtolower($novoSin)) . "', $id_projeto)";
            mysql_query($q, $connect) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        }
        
        $q = "SELECT max(id_lexicon) FROM lexicon";
        $qrr = mysql_query($q) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $result = mysql_fetch_row($qrr);
        if($linhas_afetadas == 0){
            return false;
        }else{
            return $result[0];
        }
    }
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

if (!(function_exists("adicionar_lexico"))) 
{
    function adicionar_lexico($id_projeto, $nome, $nocao, $impacto, $sinonimos, $classificacao)
    {
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $id_incluido = inclui_lexico($id_projeto, $nome, $nocao, $impacto, $sinonimos, $classificacao); // (1)
        
        $qr = "SELECT id_cenario, titulo, objetivo, contexto, atores, recursos, excecao, episodios
              FROM cenario
              WHERE id_projeto = $id_projeto";
        
        $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)) 
        {    // 2  - Para todos os cenarios
        
           $nomeEscapado = escape_metacharacter( $nome );
		   $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
         
            if( (preg_match($regex, $result['objetivo']) != 0) ||
                (preg_match($regex, $result['contexto']) != 0) ||
                (preg_match($regex, $result['atores']) != 0)   ||
                (preg_match($regex, $result['recursos']) != 0) ||
                (preg_match($regex, $result['excecao']) != 0)  ||
                (preg_match($regex, $result['episodios']) != 0) )
            { //2.2
        
                $q = "INSERT INTO centolex (id_cenario, id_lexico)
                     VALUES (" . $result['id_cenario'] . ", $id_incluido)"; //2.2.1
        
                mysql_query($q) or die("Erro ao enviar a query de INSERT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
          
            }
        }

        
        //sinonimos do novo lexico
        $count = count($sinonimos);
        for ($i = 0; $i < $count; $i++)
        {
            
            $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
            while ($result2 = mysql_fetch_array($qrr))
            {
                
                $nomeSinonimoEscapado = escape_metacharacter( $sinonimos[$i] );
				$regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                if( (preg_match($regex, $result2['objetivo']) != 0) ||
                    (preg_match($regex, $result2['contexto']) != 0) ||
                    (preg_match($regex, $result2['atores']) != 0)   ||
                    (preg_match($regex, $result2['recursos']) != 0) ||
                    (preg_match($regex, $result2['excecao']) != 0)  ||
                    (preg_match($regex, $result2['episodios']) != 0) )
                { 
                            
                    $qLex = "SELECT * FROM centolex WHERE id_cenario = " . $result2['id_cenario'] . " AND id_lexico = $id_incluido ";
                    $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    $resultArraylex = mysql_fetch_array($qrLex);
                
                    if ( $resultArraylex == false )
                    {
                    
                        $q = "INSERT INTO centolex (id_cenario, id_lexico)
                             VALUES (" . $result2['id_cenario'] . ", $id_incluido)";                   
                    
                        mysql_query($q) or die("Erro ao enviar a query de INSERT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    } //if
                }//if                
            }//while            
        } //for
        
        
        $qlo = "SELECT id_lexico, nome, nocao, impacto, tipo
               FROM lexico
               WHERE id_projeto = $id_projeto
               AND id_lexico != $id_incluido";
                     
        //pega todos os outros lexicos
        $qrr = mysql_query($qlo) or die("Erro ao enviar a query de SELECT no LEXICO<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)) 
        {    // (3)
        
            $nomeEscapado = escape_metacharacter( $nome );
            $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
            
            if ( (preg_match($regex, $result['nocao']) != 0 ) ||
                 (preg_match($regex, $result['impacto'])!= 0) )
            {
                
                $qLex = "SELECT * FROM lextolex WHERE id_lexico_from = " . $result['id_lexico'] . " AND id_lexico_to = $id_incluido";
                $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                $resultArraylex = mysql_fetch_array($qrLex);
      
                if ( $resultArraylex == false )
                {
                    $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
                          VALUES (" . $result['id_lexico'] . ", $id_incluido)";
                    
                    mysql_query($q) or die("Erro ao enviar a query de INSERT no lextolex 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                }
            }
         
			$nomeEscapado = escape_metacharacter( $result['nome'] );
            $regex = "/(\s|\b)(" . $nomeEscapado . ")(\s|\b)/i";
         
            if((preg_match($regex, $nocao) != 0) ||
               (preg_match($regex, $impacto) != 0) )
            {   // (3.3)        
        
                $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to) VALUES ($id_incluido, " . $result['id_lexico'] . ")"; 
        
                mysql_query($q) or die("Erro ao enviar a query de insert no centocen<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
            }
       
        }   // while
        
        
        //lexico para lexico
        
        $ql = "SELECT id_lexico, nome, nocao, impacto
              FROM lexico
              WHERE id_projeto = $id_projeto
              AND id_lexico != $id_incluido";                                                                     
        
        //sinonimos dos outros lexicos no texto do inserido
        
        $qrr = mysql_query($ql) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $count = count($sinonimos);
        for ($i = 0; $i < $count; $i++)
        {
            while ($resultl = mysql_fetch_array($qrr)) {
                               
				$nomeSinonimoEscapado = escape_metacharacter( $sinonimos[$i] );
			   $regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                if ( (preg_match($regex, $resultl['nocao']) != 0)  ||
                     (preg_match($regex, $resultl['impacto']) != 0))
                {
                                    
                    $qLex = "SELECT * FROM lextolex WHERE id_lexico_from = " . $resultl['id_lexico'] . " AND id_lexico_to = $id_incluido";
                    $qrLex = mysql_query($qLex) or die("Erro ao enviar a query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    $resultArraylex = mysql_fetch_array($qrLex);
                    
                    if ( $resultArraylex == false )
                    {
                        
                        $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
                         VALUES (" . $resultl['id_lexico'] . ", $id_incluido)";            
                        
                        mysql_query($q) or die("Erro ao enviar a query de insert no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
                    }//if
                }    //if
            }//while
        }//for
        
        //sinonimos ja existentes
        
        $qSinonimos = "SELECT nome, id_lexico FROM sinonimo WHERE id_projeto = $id_projeto AND id_lexico != $id_incluido AND id_pedidolex = 0";
        
        $qrrSinonimos = mysql_query($qSinonimos) or die("Erro ao enviar a query de select no sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $nomesSinonimos = array();
        
        $id_lexicoSinonimo = array();
        
        while($rowSinonimo = mysql_fetch_array($qrrSinonimos))
        {
            
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
if (!(function_exists("inserirPedidoAdicionarLexico"))) {
    function inserirPedidoAdicionarLexico($id_projeto,$nome,$nocao,$impacto,$id_usuario,$sinonimos, $classificacao){
        
        $DB = new PGDB() ;
        $insere = new QUERY($DB) ;
        $select = new QUERY($DB) ;
        $select2 = new QUERY($DB) ;
        
        $q = "SELECT * FROM participa WHERE gerente = 1 AND id_usuario = $id_usuario AND id_projeto = $id_projeto";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        
        
        if ( $resultArray == false ) //nao e gerente
        {
            
            $insere->execute("INSERT INTO pedidolex (id_projeto,nome,nocao,impacto,tipo,id_usuario,tipo_pedido,aprovado) VALUES ($id_projeto,'$nome','$nocao','$impacto','$classificacao',$id_usuario,'inserir',0)") ;
            
            $newId = $insere->getLastId();
            
            $select->execute("SELECT * FROM usuario WHERE id_usuario = '$id_usuario'") ;
            
            $select2->execute("SELECT * FROM participa WHERE gerente = 1 and id_projeto = $id_projeto") ;
            
            
            //insere sinonimos
            
            foreach($sinonimos as $sin)
			{
				$insere->execute("INSERT INTO sinonimo (id_pedidolex, nome, id_projeto) 
				VALUES ($newId, '".data_prepare(strtolower($sin))."', $id_projeto)");
            }
            //fim da insercao dos sinonimos
            
            if ($select->getntuples() == 0 &&$select2->getntuples() == 0){
                echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
            }else{
                
                $record = $select->gofirst ();
                $nome2 = $record['nome'] ;
                $email = $record['email'] ;
                $record2 = $select2->gofirst ();
                while($record2 != 'LAST_RECORD_REACHED'){
                    $id = $record2['id_usuario'] ;
                    $select->execute("SELECT * FROM usuario WHERE id_usuario = $id") ;
                    $record = $select->gofirst ();
                    $mailGerente = $record['email'] ;
                    mail("$mailGerente", "Pedido de Inclus�o de L�xico", "O usuario do sistema $nome2\nPede para inserir o lexico $nome \nObrigado!","From: $nome2\r\n"."Reply-To: $email\r\n");
                    $record2 = $select2->gonext();
                    
                    
                }
            }
            
        }else{ //Eh gerente
        	adicionar_lexico($id_projeto, $nome, $nocao, $impacto, $sinonimos, $classificacao) ;
        
        }
    }
}


###################################################################
# Essa funcao recebe um id de lexico e remove todos os seus
# links e relacionamentos existentes em todas as tabelas do banco.
###################################################################
if (!(function_exists("removeLexico"))) {
    function removeLexico($id_projeto,$id_lexicon, $lexicon_name){
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
        $linhas_afetadas = mysql_affected_rows();
        
        if($linhas_afetadas == 0){
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
        $DB = new PGDB () ;
        $insere = new QUERY ($DB) ;
        $select = new QUERY ($DB) ;
        $select2 = new QUERY ($DB) ;
        
        $q = "SELECT * FROM participa WHERE gerente = 1 AND id_usuario = $id_usuario AND id_projeto = $id_projeto";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        
        if ( $resultArray == false ) //nao e gerente
        {
        
	        $select->execute("SELECT * FROM lexico WHERE id_lexico = $id_lexico") ;
	        $lexico = $select->gofirst ();
	        $nome = $lexico['nome'] ;
	        
	        $insere->execute("INSERT INTO pedidolex (id_projeto,id_lexico,nome,id_usuario,tipo_pedido,aprovado) VALUES ($id_projeto,$id_lexico,'$nome',$id_usuario,'remover',0)") ;
	        $select->execute("SELECT * FROM usuario WHERE id_usuario = $id_usuario") ;
	        $select2->execute("SELECT * FROM participa WHERE gerente = 1 and id_projeto = $id_projeto") ;
	        
	        if ($select->getntuples() == 0&&$select2->getntuples() == 0){
	            echo "<BR> [ERRO]Pedido nao foi comunicado por e-mail." ;
	        }else{
	            $record = $select->gofirst ();
	            $nome = $record['nome'] ;
	            $email = $record['email'] ;
	            $record2 = $select2->gofirst ();
	            while($record2 != 'LAST_RECORD_REACHED'){
	                $id = $record2['id_usuario'] ;
	                $select->execute("SELECT * FROM usuario WHERE id_usuario = $id") ;
	                $record = $select->gofirst ();
	                $mailGerente = $record['email'] ;
	                mail("$mailGerente", "Pedido de Remover L�xico", "O usuario do sistema $nome2\nPede para remover o lexico $id_lexico \nObrigado!","From: $nome\r\n"."Reply-To: $email\r\n");
	                $record2 = $select2->gonext();
	            }
	        }
        }else{ // e gerente
        	removeLexico($id_projeto,$id_lexico, null);
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
if (!(function_exists("tratarPedidoLexico"))) {
    function tratarPedidoLexico($id_pedido){
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $delete = new QUERY ($DB);
        $selectSin = new QUERY ($DB);
        $select->execute("SELECT * FROM pedidolex WHERE id_pedido = $id_pedido") ;
        if ($select->getntuples() == 0){
            echo "<BR> [ERRO]Pedido invalido." ;
        }else{
            $record = $select->gofirst () ;
            $tipoPedido = $record['tipo_pedido'] ;
            if(!strcasecmp($tipoPedido,'remover')){
                $id_lexico = $record['id_lexico'] ;
                $id_projeto = $record['id_projeto'] ;
                removeLexico($id_projeto,$id_lexico, null) ;
            }else{
                $id_projeto = $record['id_projeto'] ;
                $nome = $record['nome'] ;
                $nocao = $record['nocao'] ;
                $impacto = $record['impacto'] ;
                $classificacao = $record['tipo'];
                
                //sinonimos
                
                $sinonimos = array();
                $selectSin->execute("SELECT nome FROM sinonimo WHERE id_pedidolex = $id_pedido");
                $sinonimo = $selectSin->gofirst();
                if ($selectSin->getntuples() != 0)
		{
               	    while($sinonimo != 'LAST_RECORD_REACHED')
               	    {
                        $sinonimos[] = $sinonimo["nome"];
                        $sinonimo = $selectSin->gonext();
                    }
                }
                
                if(!strcasecmp($tipoPedido,'alterar')){
                    $id_lexico = $record['id_lexico'] ;
                    alteraLexico($id_projeto, $id_lexico, $nome, $nocao, $impacto, $sinonimos, $classificacao);
                }else if(($idLexicoConflitante = adicionar_lexico($id_projeto, $nome, $nocao, $impacto, $sinonimos, $classificacao)) <= 0)
                {
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
?>


