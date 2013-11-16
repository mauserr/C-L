<?php
include_once("bd.inc");
include_once("bd_class.php");

require_once '/security.php'; //("security.php");
require_once '/Functions/concept_Functions.php';
require_once '/Functions/scenario_Functions.php';
require_once '/Functions/lexicon_Functions.php';
include("/Functions/recarrega.php");


###################################################################
# Insere um lexico no banco de dados.
# Recebe o id_projeto, nome, no��o, impacto e os sinonimos. (1.1)
# Insere os valores do lexico na tabela LEXICO. (1.2)
# Insere todos os sinonimos na tabela SINONIMO. (1.3)
# Devolve o id_lexico. (1.4)
#
###################################################################


if (!(function_exists("recarrega"))) 
{
    function recarrega($url) 
    {
            assert(is_string($url));
            assertNotNull($url);
		?>
		
		<script language="javascript1.3">
		
		location.replace('<?=$url?>');
		
		</script>
		
		<?php
    }
}



if (!(function_exists("simple_query")))
{
    funcTion simple_query($field, $table, $where)
    {
        assert(is_string($field, $table, $where));
        assertNotNull($field, $table, $where);
        
        $connect = bd_connect() or die("Erro ao conectar ao SGBD");
        $query_sql = "SELECT $field FROM $table WHERE $where";
        $query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query");
        $result = mysql_fetch_row($query_result_sql);
        return $result[0];        
    }
}



###################################################################
# Essa funcao recebe um id de lexico e remove todos os seus
# links e relacionamentos existentes em todas as tabelas do banco.
###################################################################

if (!(function_exists("alteraLexico")))
{
    function alteraLexico($id_projeto, $id_lexico, $name, $nocao, $impacto, $sinonimos, $classificacao)
    {
        assert(is_int($id_projeto, $id_lexico));
        assert(is_string($name, $nocao, $impacto, $sinonimos, $classificacao));
        assertNotNull($id_projeto, $id_lexico, $name, $nocao, $impacto, $sinonimos, $classificacao);
        
        
        $DB = new PGDB () ;
        $delete = new QUERY ($DB) ;        
        
        # Remove os relacionamento existentes anteriormente
        
        $delete->execute ("DELETE FROM lextolex WHERE id_lexico_from = $id_lexico") ;
        $delete->execute ("DELETE FROM lextolex WHERE id_lexico_to = $id_lexico") ;
        $delete->execute ("DELETE FROM centolex WHERE id_lexico = $id_lexico") ;
        
        # Remove todos os sinonimos cadastrados anteriormente
        
        $delete->execute ("DELETE FROM sinonimo WHERE id_lexico = $id_lexico") ;
        
        # Altera o lexico escolhido
               
        $delete->execute ("UPDATE lexico SET 
		nocao = '".data_prepare($nocao)."', 
		impacto = '".data_prepare($impacto)."', 
		tipo = '$classificacao' 
		where id_lexico = $id_lexico");
        
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
 	    # Fim altera lexico escolhido
 	    
 	    ### VERIFICACAO DE OCORRENCIA EM CENARIOS ###
 	    
 	    ########
 	    
 	    # Verifica se h� alguma ocorrencia do titulo do lexico nos cenarios existentes no banco
       
        $qr = "SELECT id_cenario, titulo, objetivo, contexto, atores, recursos, excecao, episodios
              FROM cenario
              WHERE id_projeto = $id_projeto";
        
        $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)) 
        {    // 2  - Para todos os cenarios
        
            $nameEscapado = escape_metacharacter( $name );
			$regex = "/(\s|\b)(" . $nameEscapado . ")(\s|\b)/i";
         
            if( (preg_match($regex, $result['objetivo']) != 0) ||
                (preg_match($regex, $result['contexto']) != 0) ||
                (preg_match($regex, $result['atores']) != 0)   ||
                (preg_match($regex, $result['recursos']) != 0) ||
                (preg_match($regex, $result['excecao']) != 0)  ||
                (preg_match($regex, $result['episodios']) != 0) )
            { //2.2
        
                $q = "INSERT INTO centolex (id_cenario, id_lexico)
                     VALUES (" . $result['id_cenario'] . ", $id_lexico)"; //2.2.1
        
                mysql_query($q) or die("Erro ao enviar a query de INSERT 1<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
          
            }//if
        }//while

		# Fim da verificacao
        
        ########
 	    
 	    # Verifica se h� alguma ocorrencia de algum dos sinonimos do lexico nos cenarios existentes no banco
       
        //&sininonimos = sinonimos do novo lexico
        $count = count($sinonimos);
        for ($i = 0; $i < $count; $i++)//Para cada sinonimo
        {
            $qrr = mysql_query($qr) or die("Erro ao enviar a query de SELECT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
            while ($result2 = mysql_fetch_array($qrr))// para cada cenario
            {
                
                $nomeSinonimoEscapado = escape_metacharacter ( $sinonimos[$i] );
				$regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                if( (preg_match($regex, $result2['objetivo']) != 0) ||
                    (preg_match($regex, $result2['contexto']) != 0) ||
                    (preg_match($regex, $result2['atores']) != 0)   ||
                    (preg_match($regex, $result2['recursos']) != 0) ||
                    (preg_match($regex, $result2['excecao']) != 0)  ||
                    (preg_match($regex, $result2['episodios']) != 0) )
                { 
                   // $q = "INSERT INTO centolex (id_cenario, id_lexico)
                   //      VALUES (" . $result2['id_cenario'] . ", $id_lexico)";                   
                
                  //  mysql_query($q) or die("Erro ao enviar a query de INSERT 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
    
                }//if                
            }//while            
        } //for
        
        # Fim da verificacao
        
        ########
        
        ### VERIFICACAO DE OCORRENCIA EM LEXICOS
        
        ########
 	    
 	    # Verifica a ocorrencia do titulo do lexico alterado no texto dos outros lexicos
        # Verifica a ocorrencia do titulo dos outros lexicos no lexico alterado
        
        //select para pegar todos os outros lexicos
        $qlo = "SELECT id_lexico, nome, nocao, impacto, tipo
               FROM lexico
               WHERE id_projeto = $id_projeto
               AND id_lexico <> $id_lexico";
                     
        $qrr = mysql_query($qlo) or die("Erro ao enviar a query de SELECT no LEXICO<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        while ($result = mysql_fetch_array($qrr)) // para cada lexico exceto o que esta sendo alterado
        {    // (3)
        
        	# Verifica a ocorrencia do titulo do lexico alterado no texto dos outros lexicos
        	        
            $nameEscapado = escape_metacharacter( $name );
			$regex = "/(\s|\b)(" . $nameEscapado . ")(\s|\b)/i";
            
            if ( (preg_match($regex, $result['nocao']) != 0 ) ||
                 (preg_match($regex, $result['impacto'])!= 0) )
            {
                $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
                      	VALUES (" . $result['id_lexico'] . ", $id_lexico)";
                
                mysql_query($q) or die("Erro ao enviar a query de INSERT no lextolex 2<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	        }
         
            # Verifica a ocorrencia do titulo dos outros lexicos no texto do lexico alterado
            
			$nameEscapado = escape_metacharacter( $result['nome'] );
            $regex = "/(\s|\b)(" . $nameEscapado . ")(\s|\b)/i";
         
            if((preg_match($regex, $nocao) != 0) ||
               (preg_match($regex, $impacto) != 0) )
            {   // (3.3)        
        
                $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to) 
                		VALUES ($id_lexico, " . $result['id_lexico'] . ")"; 
        
                mysql_query($q) or die("Erro ao enviar a query de insert no centocen<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__); 
            }
       
        }// while
        
        # Fim da verificao por titulo
        
        $ql = "SELECT id_lexico, nome, nocao, impacto
              FROM lexico
              WHERE id_projeto = $id_projeto
              AND id_lexico <> $id_lexico";                                                                     
        
        # Verifica a ocorrencia dos sinonimos do lexico alterado nos outros lexicos
       
        $count = count($sinonimos);
        for ($i = 0; $i < $count; $i++)// para cada sinonimo do lexico alterado
        {
         	
			$qrr = mysql_query($ql) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);        
            while ($resultl = mysql_fetch_array($qrr)) 
            {// para cada lexico exceto o alterado
                $nomeSinonimoEscapado = escape_metacharacter( $sinonimos[$i] );
				$regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
                
                // verifica sinonimo[i] do lexico alterado no texto de cada lexico
             
                if ( (preg_match($regex, $resultl['nocao']) != 0)  ||
                     (preg_match($regex, $resultl['impacto']) != 0))
                {
					
					 // Verifica  se a relacao encontrada ja se encontra no banco de dados. Se tiver nao faz nada, senao cadastra uma nopva relacao
					$qverif = "SELECT * FROM lextolex where id_lexico_from=" . $resultl['id_lexico'] . " and id_lexico_to=$id_lexico";
					echo("Query: ".$qverif."<br>");
					$resultado = mysql_query($qverif) or die("Erro ao enviar query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);	
					if(!resultado)
					{
						$q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
	                     VALUES (" . $resultl['id_lexico'] . ", $id_lexico)";            
						mysql_query($q) or die("Erro ao enviar a query de insert(sinonimo2) no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);	
				  }
                    
                }//if
            }//while
        }//for
    	
    	# Verifica a ocorrencia dos sinonimos dos outros lexicos no lexico alterado
        
        $qSinonimos = "SELECT nome, id_lexico 
        		FROM sinonimo 
        		WHERE id_projeto = $id_projeto 
        		AND id_lexico <> $id_lexico 
        		AND id_pedidolex = 0";
        
        $qrrSinonimos = mysql_query($qSinonimos) or die("Erro ao enviar a query de select no sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        
        $namesSinonimos = array();
        $id_lexicoSinonimo = array();
        
        while($rowSinonimo = mysql_fetch_array($qrrSinonimos))
        {
        	$nomeSinonimoEscapado = escape_metacharacter( $rowSinonimo["nome"] );
			$regex = "/(\s|\b)(" . $nomeSinonimoEscapado . ")(\s|\b)/i";
        
        	if((preg_match($regex, $nocao) != 0) ||
               (preg_match($regex, $impacto) != 0)){
               
               // Verifica  se a relacao encontrada ja se encontra no banco de dados. Se tiver nao faz nada, senao cadastra uma nopva relacao
			   $qv = "SELECT * FROM lextolex where id_lexico_from=$id_lexico and id_lexico_to=".$rowSinonimo['id_lexico'];
			   $resultado = mysql_query($qv) or die("Erro ao enviar query de select no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);	
			   if(!resultado)
			   {		
				   $q = "INSERT INTO lextolex (id_lexico_from, id_lexico_to)
	                     VALUES ($id_lexico, " . $rowSinonimo['id_lexico'] . ")";            
	                    
					mysql_query($q) or die("Erro ao enviar a query de insert(sinonimo) no lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);	
               }
		   }
        }    
    
        # Cadastra os sinonimos novamente
        
        if(!is_array($sinonimos) )
        	$sinonimos = array();
        
        foreach($sinonimos as $novoSin)
        {
         	$q = "INSERT INTO sinonimo (id_lexicon, name, id_project)
                VALUES ($id_lexico, '". data_prepare(strtolower($novoSin))."', $id_projeto)";
            
            mysql_query($q, $connect) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        }
     
		# Fim - cadastro de sinonimos        
        
                
    }
}




###################################################################
# Recebe o id do projeto e a lista de sinonimos (1.0)
# Funcao faz um select na tabela sinonimo.
# Para verificar se ja existe um sinonimo igual no BD.
# Faz um SELECT na tabela lexico para verificar se ja existe
# um lexico com o mesmo nome do sinonimo.(1.1)
# retorna true caso nao exista ou false caso exista (1.2)
###################################################################
function checarSinonimo($project, $listSinonimo)
{
    assert(is_string($project));
    assertNotNull($project, $listSinonimo);
    
    
    $naoexiste = true;
    
    $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
    
    foreach($listSinonimo as $sinonimo){
        
        $q = "SELECT * FROM sinonimo WHERE id_projeto = $project AND nome = '$sinonimo' ";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        if ( $resultArray != false )
        {
            $naoexiste = false;
            return $naoexiste;
        }
        
        $q = "SELECT * FROM lexico WHERE id_projeto = $project AND nome = '$sinonimo' ";
        $qr = mysql_query($q) or die("Erro ao enviar a query de select no sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $resultArray = mysql_fetch_array($qr);
        if ( $resultArray != false )
        {
            $naoexiste = false;
            return $naoexiste;
        }
    }
    
    return $naoexiste;
    
    
}



###################################################################
# Funcao faz um insert na tabela de pedido.
# Para remover uma relacao ela deve receber
# o id da relacao e id projeto.(1.1)
# Ao final ela manda um e-mail para o gerente do projeto
# referente a este relacao.(2.1)
# Arquivos que utilizam essa funcao:
# rmv_relacao.php
###################################################################
if (!(function_exists("insertRequestRemoveRelation"))) {
    function insertRequestRemoveRelation($id_projeto,$id_relacao,$id_usuario){
        assert(is_int($id_projeto, $id_relacao, $id_usuario));
        assertNotNull($id_projeto,$id_relacao,$id_usuario);
        
        
        
        $DB = new PGDB () ;
        $insere = new QUERY ($DB) ;
        $select = new QUERY ($DB) ;
        $select2 = new QUERY ($DB) ;
        $select->execute("SELECT * FROM relacao WHERE id_relacao = $id_relacao") ;
        $relacao = $select->gofirst ();
        $name = $relacao['nome'] ;
        
        $insere->execute("INSERT INTO pedidorel (id_projeto,id_relacao,nome,id_usuario,tipo_pedido,aprovado) VALUES ($id_projeto,$id_relacao,'$name',$id_usuario,'remover',0)") ;
        $select->execute("SELECT * FROM usuario WHERE id_usuario = $id_usuario") ;
        $select2->execute("SELECT * FROM participa WHERE gerente = 1 and id_projeto = $id_projeto") ;
        
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
                mail("$mailGerente", "Pedido de Remover Conceito", "O usuario do sistema $name2\nPede para remover o conceito $id_relacao \nObrigado!","From: $name\r\n"."Reply-To: $email\r\n");
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
if (!(function_exists("tratarPedidoRelacao"))) {
    function tratarPedidoRelacao($id_pedido){
        assert(is_int($id_pedido));
        assertNotNull($id_pedido);
        
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $delete = new QUERY ($DB) ;
        $select->execute("SELECT * FROM pedidorel WHERE id_pedido = $id_pedido") ;
        if ($select->getntuples() == 0){
            echo "<BR> [ERRO]Pedido invalido." ;
        }else{
            $record = $select->gofirst () ;
            $tipoPedido = $record['tipo_pedido'] ;
            if(!strcasecmp($tipoPedido,'remover')){
                $id_relacao = $record['id_relacao'] ;
                $id_projeto = $record['id_projeto'] ;
                removeRelacao($id_projeto,$id_relacao) ;
            }else{
                
                $id_projeto = $record['id_projeto'] ;
                $name         = $record['nome'] ;
                                
                if(!strcasecmp($tipoPedido,'alterar')){
                    $id_relacao = $record['id_relacao'] ;
                    removeRelacao($id_projeto,$id_relacao) ;
                }
                adicionar_relacao($id_projeto, $name) ;
            }
        }
    }
}
#############################################
#Deprecated by the author:
#Essa funcao deveria receber um id_projeto
#de forma a verificar se o gerente pertence
#a esse projeto.Ela so verifica atualmente
#se a pessoa e um gerente.
#############################################
if (!(function_exists("verifyManager"))) {
    function verifyManager($id_user){
        assert(is_int($id_user));
        assertNotNull($id_user);
        
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $select->execute("SELECT * FROM participates WHERE manager = 1 AND id_user = $id_user") ;
        if ($select->getntuples() == 0){
            return 0 ;
        }else{
            return 1 ;
        }
    }
}

#############################################
# Formata Data
# Recebe YYY-DD-MM
# Retorna DD-MM-YYYY
#############################################
if (!(function_exists("formataData"))) {
    function formataData($data){
        
        $novaData = substr( $data, 8, 9 ) .
        substr( $data, 4, 4 ) .
        substr( $data, 0, 4 );
        return $novaData ;
    }
}


// Retorna TRUE ssse $id_usuario eh admin de $id_projeto
if (!(function_exists("is_admin"))) {
    function is_admin($id_usuario, $id_projeto)
    {
        assert(is_int($id_usuario, $id_projeto));
        assertNotNull($id_usuario, $id_projeto);
        
        
        $connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        $q = "SELECT *
              FROM participates
              WHERE id_user = $id_usuario
              AND id_project = $id_projeto
              AND manager = 1";
        $qrr = mysql_query($q) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
        return (1 == mysql_num_rows($qrr));
    }
}




###################################################################
# Verifica se um determinado usuario e gerente de um determinado
# projeto
# Recebe o id do projeto. (1.1)
# Faz um select para pegar o resultArray da tabela Participa.(1.2)
# Se o resultArray for nao nulo: devolvemos TRUE(1);(1.3)
# Se o resultArray for nulo: devolvemos False(0);(1.4)
###################################################################

function verificaGerente($id_usuario, $id_projeto)
{
    assert(is_int($id_usuario, $id_projeto));
    assertNotNull($id_usuario, $id_projeto);
    
    $ret = 0;
    
    $q = "SELECT * FROM participates WHERE manager = 1 AND id_user = $id_usuario AND id_project = $id_projeto";
    $qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
    $resultArray = mysql_fetch_array($qr);
    
    if ( $resultArray != false ){
        
        $ret = 1;
    }
    return $ret;    
}

?>
