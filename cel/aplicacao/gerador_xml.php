<?php

session_start();

include("funcoes_genericas.php");
include_once("puts_Links.php");
include("Functions/reload_Page.php");
include("httprequest.inc");
include_once("bd.inc");
include_once("seguranca.php");

chkUser("index.php");        // Checa se o usuario foi autenticado
      

if( isset( $_POST['flag']))
{
    $flag = "ON";
}
else
{
    $flag = "OFF";
}


?>

<?php

// gerador_xml.php

// Dada a base e o id do projeto, gera-se o xml

// dos cen�rios e l�xicos.

//Cen�rio - Gerar Relat�rios XML 

//Objetivo:    Permitir ao administrador gerar relat�rios em formato XML de um projeto, identificados por data.     
//Contexto:    Gerente deseja gerar um relat�rio para um dos projetos da qual � administrador.
//          Pr�-Condi��o: Login, projeto cadastrado.
//Atores:    Administrador     
//Recursos:    Sistema, dados do relat�rio, dados cadastrados do projeto, banco de dados.     
//Epis�dios:O sistema fornece para o administrador uma tela onde dever� fornecer os dados
//          do relat�rio para sua posterior identifica��o, como data e vers�o. 
//          Para efetivar a gera��o do relat�rio, basta clicar em Gerar. 
//          Restri��o: O sistema executar� duas valida��es: 
//                      - Se a data � v�lida.
//                      - Se existem cen�rios e l�xicos em datas iguais ou anteriores.
//          Gerando com sucesso o relat�rio a partir dos dados cadastrados do projeto,
//          o sistema fornece ao administrador a tela de visualiza��o do relat�rio XML criado. 
//          Restri��o: Recuperar os dados em XML do Banco de dados e os transformar por uma XSL para a exibi��o.      

if (!(function_exists("gerar_xml"))) {
    function gerar_xml( $db, $id_project, $search_date, $formated_flag)
    {
        $result_xml = "";
		$vetorVazio = array();
       
        if ($formated_flag == "ON")
        {
			$result_xml = "";
			$result_xml = $result_xml . "<?xml-stylesheet type='text/xsl' href='projeto.xsl'?>\n" ;
        }
        $result_xml = $result_xml . "<project>\n" ;

        // Seleciona o nome do projeto

	    $query_select_name = "SELECT name
                     FROM project
                     WHERE id_project = " . $id_project ;
	    $tb_nome = mysql_query ( $query_select_name ) or die ( "Erro ao enviar a query de selecao." ) ;

        // Adiciona o nome do projeto no xml		
		$result_xml = $result_xml . "<name>" . mysql_result ( $tb_nome, 0 ) . "</name>\n"; 

        ## CEN�RIOS ##
        
        // Seleciona os cen�rios de um projeto.

        $qry_cenario = "SELECT id_scenario ,
                               title ,
                               objective ,
                               context ,
                               actors ,
                               resources ,
                               episodios ,
                               exception
                        FROM   scenario
                        WHERE  (id_project = " . $id_project
                        . ") AND (data <=" . " '" . $search_date . "'". ")
                        ORDER BY id_scenario,data DESC";

        $tb_cenario = mysql_query( $qry_cenario ) or die( "Erro ao enviar a query de selecao." ) ;

        $first = true;

        $id_temp = "";
		
		$vetor_todos_lexicos = carrega_vetor_lexicos($id_project, 0, false);

		// Para cada cen�rio
		
  	    while ( $row = mysql_fetch_row( $tb_cenario ) ) 
        {
            $id_scenario = "<ID>" . $row[ 0 ] . "</ID>" ;
            $current_scenario_id = $row[ 0 ];
            $scenarios_vector = carrega_vetor_cenario( $id_project, $current_scenario_id,true );
            
            // Porque usa $id_temp != $id_scenario ? e a variavel primeiro
            
            if (($id_temp != $id_scenario) or ($first))
            {
                $title = '<title id="' . strtr(strip_tags ( $row[ 1 ] ),"����������","aaaaoooeec") . '">' . ucwords(strip_tags ( $row[ 1 ] )) . '</title>' ;

                $objective = "<objective>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 2 ], $vetor_todos_lexicos, $vetorVazio ) ) . "</sentenca>" . "<PT/>" . "</objective>" ;
																		   			
                $context = "<context>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 3 ], $vetor_todos_lexicos, $scenarios_vector ) ) . "</sentenca>" . "<PT/>" . "</context>" ;

                $actors = "<actors>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 4 ], $vetor_todos_lexicos, $vetorVazio ) ) . "</sentenca>" . "<PT/>" . "</actors>" ;

                $resources = "<resources>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 5 ], $vetor_todos_lexicos, $vetorVazio ) ) . "</sentenca>" . "<PT/>" . "</resources>" ;

                $exception = "<exception>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 7 ], $vetor_todos_lexicos, $vetorVazio ) ) . "</sentenca>" . "<PT/>" . "</exception>" ;

                $episodios = "<episodios>" . "<sentenca>" . gera_xml_links ( monta_links ( $row[ 6 ], $vetor_todos_lexicos, $scenarios_vector ) ) . "</sentenca>" . "<PT/>" . "</episodios>" ;
																			 
                $result_xml = $result_xml . "<scenario>\n" ;

                $result_xml = $result_xml . "$title\n" ;

                $result_xml = $result_xml . "$objective\n" ;

                $result_xml = $result_xml . "$context\n" ;

                $result_xml = $result_xml . "$actors\n" ;

                $result_xml = $result_xml . "$resources\n" ;

                $result_xml = $result_xml . "$episodios\n" ;
                
                $result_xml = $result_xml . "$exception\n" ;

                $result_xml = $result_xml . "</scenario>\n" ;

                $first = false;

                //??$id_temp = id_scenario;
           }
        
        } // while dos cen�rios
        
        // Seleciona os lexicos de um projeto.
        
        $qry_lexico = "SELECT id_lexicon ,
                               name ,
                               notion ,
                               impact
                        FROM   lexicon
                        WHERE  (id_project = " . $id_project .

                ") AND (data <=" . " '" . $search_date . "'". ")

                ORDER BY id_lexicon,data DESC";

        $tb_lexico = mysql_query( $qry_lexico ) or die( "Erro ao enviar a query de selecao." ) ;

        $first = true;

        $id_temp = "";
	
		// Para cada simbolo do lexico
	
        while ( $row = mysql_fetch_row( $tb_lexico ) ) 
        {
			$vetor_lexicos = carrega_vetor_lexicos( $id_project, $row[ 0 ],true );
			quicksort( $vetor_lexicos, 0, count($vetor_lexicos)-1,'lexicon' );
        	$id_lexicon = "<ID>" . $row[ 0 ] . "</ID>" ;
            if (($id_temp != $id_lexicon) or (primeiro))
            {

				$name = '<nome_simbolo id="' . strtr(strip_tags ( $row[ 1 ] ),"����������","aaaaoooeec") . '">' . '<texto>' . ucwords(strip_tags ( $row[ 1 ] )) . '</texto>' . '</nome_simbolo>' ;

				
				// Consulta os sinonimos do simbolo
				$querySinonimo = "SELECT name 
									FROM synonym
									WHERE (id_project = " . $id_project . ") 
									AND (id_lexicon = " . $row[0] ." )";	
				
				$resultSinonimos = mysql_query( $querySinonimo ) or die( "Erro ao enviar a query de selecao de sinonimos." ) ;
				
				//Para cada sinonimo do simbolo
				$synonym = "<synonyms>";
				
				while ( $rowSin = mysql_fetch_row( $resultSinonimos ) ) 
				{
					$synonym .= "<synonym>" . $rowSin[0] . "</synonym>";
				}
				$synonym .= "</synonyms>";  
				
				$notion = "<notion>" . "<sentenca>" . gera_xml_links ( monta_links( $row[ 2 ], $vetor_lexicos, $vetorVazio ) ) . "<PT/>" . "</sentenca>" . "</notion>" ;

				$impact = "<impact>" . "<sentenca>" . gera_xml_links ( monta_links( $row[ 3 ], $vetor_lexicos, $vetorVazio )) . "<PT/>" . "</sentenca>" . "</impact>" ;

                $result_xml = $result_xml . "<lexicon>\n" ;

                $result_xml = $result_xml . "$name\n" ;

                $result_xml = $result_xml . "$synonym\n";
				
				$result_xml = $result_xml . "$notion\n" ;

                $result_xml = $result_xml . "$impact\n" ;

                $result_xml = $result_xml . "</lexicon>\n" ;

                $first = false;

                //$id_temp = id_lexicon;
            }

        } // while

        $result_xml = $result_xml . "</project>\n" ;

        return $result_xml ;

    } // gerar_xml
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//
//Cen�rio - Gerar links nos Relat�rios XML criados
//
//Objetivo:    Permitir que os relat�rios gerados em formato XML possuam termos com links 
//          para os seus respectivos l�xicos
//
//Contexto:    Gerente deseja gerar um relat�rio em XML para um dos projetos da qual � administrador.
//          Pr�-Condi��o: Login, projeto cadastrado, acesso ao banco de dados.
//
//Atores:    Sistema    
//
//Recursos:    Sistema, senten�as a serem linkadas, dados cadastrados do projeto, banco de dados. 
//    
//Epis�dios:O sistema recebe a senten�a com os tags pr�prios do C&L e retorna o c�digo do link HTML
//            equivalente para os l�xicos cadatrados no sistema. 
//     
///////////////////////////////////////////////////////////////////////////////////////////////////
//
//L�xicos:
//
//     Fun��o:            gera_xml_links
//     Descri��o:         Analisa uma senten�a recebida afim de identificar as tags utilizadas no C&L
//                        para linkar os l�xicos e transformar em links XML.
//     Sin�nimos:         -
//     Exemplo: 
//        ENTRADA: <!--CL:tam:2--><a title="Lexico" href="main.php?t=l&id=228">software livre</a>
//                 <!--/CL-->
//        SA�DA:  <a title="Lexico" href="main.php?t=l&id=228"><texto referencia_lexico=software 
//                livre>software livre</texto></a>
//
//     Vari�vel:            $sentenca
//     Descri��o:         Armazena a express�o passada por argumento a ser tranformada em link.
//     Sin�nimos:         -
//     Exemplo:             <!--CL:tam:2--><a title="Lexico" href="main.php?t=l&id=228">software livre
//                        </a><!--/CL-->
//
//     Vari�vel:            $regex
//     Descri��o:            Armazena o pattern a ser utilizado ao se separar a senten�a.
//     Sin�nimos:            -
//     Exemplo:            "/(<!--CL:tam:\d+-->(<a[^>]*?\>)([^<]*?)<\/a><!--\/CL-->)/mi"
//
//     Vari�vel:            $vector_size
//     Descri��o:         Array que armazena palavra por palavra a sente�a a ser linkada, sem o tag.
//     Sin�nimos:         -
//     Exemplo:             $vector_size[0] => software
//                        $vector_size[1] => livre
//
//     Vari�vel:            $inside_tag
//     Descri��o:         Determina se a an�lise est� sendo feita dentro ou fora do tag
//     Sin�nimos:         -
//     Exemplo:             false
//
//     Vari�vel:            $vector_text_size
//     Descri��o:         Armazena a n�mero de palavras que se encontram no array $vector_size. 
//     Sin�nimos:         -
//     Exemplo:             2
//
//     Vari�vel:            $i
//     Descri��o:         Vari�vel utilizada como um contador para uso gen�rico.
//     Sin�nimos:         -
//     Exemplo:             -
//
//     Vari�vel:            $match
//     Descri��o:         Armazena o valor 1 caso a string "/href="main.php\?t=(.)&id=(\d+?)"/mi"
//                        seja encontrada na no array $vector_size. Caso contr�rio, armazena 0.
//     Sin�nimos:         -
//     Exemplo:             0
//
//     Vari�vel:            $id_project
//     Descri��o:         Armazena o n�mero identificador do projeto corrente.
//     Sin�nimos:         -
//     Exemplo:             1
//
//     Vari�vel:            $atributo
//     Descri��o:         Armazena um tag que indica a refer�ncia para um l�xico
//     Sin�nimos:         -
//     Exemplo:             referencia_lexico
//
//     Vari�vel:            $query
//     Descri��o:         Armazena a consulta a ser feita no banco de dados
//     Sin�nimos:         -
//     Exemplo:             SELECT nome FROM lexico WHERE id_project = $id_project
//
//     Vari�vel:            $result
//     Descri��o:         Armazena o resultado da consulta feita ao banco de dados
//     Sin�nimos:         -
//     Exemplo:             -
//
//     Vari�vel:            $row
//     Descri��o:         Array que armazena tupla a tupla o resultado da consulta realizada
//     Sin�nimos:         -
//     Exemplo:             -
//
//     Vari�vel:            $valor
//     Descri��o:         Armazena uma tupla, substituindo os caracteres acentuados pelos seus 
//                        equivalentes sem acentua��o.
//     Sin�nimos:         -
//     Exemplo:             acentuacao
//
///////////////////////////////////////////////////////////////////////////////////////////////////


if (!(function_exists("gera_xml_links"))) {
    function gera_xml_links($sentenca)
    {
        
        if (trim($sentenca)!="")
        {
        
        	$regex = "/(<a[^>]*?>)(.*?)<\/a>/";
	
            $vector_size = preg_split($regex, $sentenca, -1, PREG_SPLIT_DELIM_CAPTURE);
            $vector_text_size = count($vector_size);
            $i = 0;
            
                 
            while ($i < $vector_text_size )
            {
           		preg_match('/href="main.php\?t=(.)&id=(\d+?)"/mi', $vector_size[$i], $match);
                if($match)
                {
                    $id_project = $_SESSION['current_id_project'];
                        
                    // Verifica se � l�xico 
                    if($match[1]=='l')
                    {
                        // Retira o link do texto
                       $vector_size[$i]="";
                        
                        //link para l�xico
                        $atributo = "referencia_lexico";                        
                            
                        $query = "SELECT name FROM lexicon WHERE id_project = $id_project AND id_lexicon = $match[2] ";
                        $result = mysql_query($query) or die("Erro ao enviar a query lexico");
                        $row = mysql_fetch_row($result);
                    	// Pega o nome do l�xico
                        $valor = strtr( $row[ 0 ] ,"����������","aaaaoooeec");
                            
                        $vector_size[$i+1] = '<texto '.$atributo.'="'.$valor.'">'.$vector_size[$i+1].'</texto>';
                    } else if($match[1]=='c')
                    {
                        // Retira o link do texto
                        $vector_size[$i]="";
                        
                        //link para cen�rio
                        $atributo = "referencia_cenario";                        
                            
                        $query = "SELECT title FROM scenario WHERE id_project = $id_project AND id_scenario = $match[2] ";
                        $result = mysql_query($query) or die("Erro ao enviar a query cenario");
                        $row = mysql_fetch_row($result);
                        // Pega o titulo do cenario
                        $valor = strtr( $row[ 0 ] ,"����������","aaaaoooeec");
                            
                        $vector_size[$i+1] = '<texto '.$atributo.'="'.$valor.'">'.strip_tags($vector_size[$i+1]).'</texto>';
                    }
                    
                 	$i = $i+2;   
                }
                else
                {
                    if (trim($vector_size[$i])!="")
                    {
                        $vector_size[$i] = "<texto>".$vector_size[$i]."</texto>";
                    }
                    
                    $i = $i+1;
                }
            }
            // Junta os elementos do array vetor_texto em uma string
            return implode("", $vector_size);
        }
        return $sentenca;
    }
}
?>

<?php

    $id_project = $_SESSION['current_id_project'];
    $search_date = $year_date . "-" . $month_date . "-" . $day_date;
    $formated_flag = $flag;

    // Abre base de dados.
      $bd_trabalho = bd_connect() or die("Erro ao conectar ao SGBD");
      
      $qVerifica = "SELECT * FROM publication WHERE id_project = '$id_project' AND version = '$version' ";
      $qrrVerifica = mysql_query($qVerifica);

		// Se n�o existir nenhum XML com o id passado ele cria
        if ( !mysql_num_rows($qrrVerifica) )
        {

			$str_xml = gerar_xml( $bd_trabalho , $id_project,  $search_date, $formated_flag ) ;
           
			$result_xml = "<?xml version='1.0' encoding='ISO-8859-1' ?>\n".$str_xml ;
			
            $q = "INSERT INTO publication ( id_project, date_publication, version, XML)
                 VALUES ( '$id_project', '$search_date', '$version', '".mysql_real_escape_string($result_xml)."')";
              
			mysql_query($q) or die("Erro ao enviar a query INSERT do XML no banco de dados! ");
			reload_Page("http://pes.inf.puc-rio.br/cel/aplicacao/mostraXML.php?id_project=".$id_project."&version=".$version);
		}
    else
    {
    ?>
    <html><head><title>Projeto</title></head><body bgcolor="#FFFFFF">
    <p style="color: red; font-weight: bold; text-align: center">Essa vers�o j� existe!</p>
    <br>
    <br>
        <center><a href="JavaScript:window.history.go(-1)">Voltar</a></center>
    </body></html>
    
    <?php
    }   
?> 
