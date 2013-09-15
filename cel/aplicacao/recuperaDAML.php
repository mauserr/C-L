<HTML> 
<HEAD> 
<LINK rel="stylesheet" type="text/css" href="style.css"> 
<TITLE>Recuperação de Arquivos DAML</TITLE> 
</HEAD> 

<BODY> 
<H2>Histórico de Arquivos DAML</H2> 
<?PHP 

    include_once( "CELConfig/CELConfig.inc" ) ;
    /* 
        Arquivo   : recuperaDAML.php 
        Versão       : 1.0 
        Comentário: Este programa lista todos os arquivos DAML    gerados    em $_SESSION['diretorio'] 
    */ 
     
    function extract_date ($name_fle ) 
    { 
        list($project, $rest) = split("__", $name_file);
        list($day, $month, $year, $hour, $minute, $second, $extension) = split('[_-.]', $rest); 
         
        if( !is_numeric($day) || !is_numeric($month) || !is_numeric($year) || !is_numeric($hour) || !is_numeric($minute) || !is_numeric($second) ) 
            return "-"; 
         
        $months_spelled = "-"; 
        switch( $month ) 
        { 
            case 1: $months_spelled = "janeiro"; break; 
            case 2: $months_spelled = "fevereiro"; break; 
            case 3: $months_spelled = "março"; break; 
            case 4: $months_spelled = "abril"; break; 
            case 5: $months_spelled = "maio"; break; 
            case 6: $months_spelled = "junho"; break; 
            case 7: $months_spelled = "julho"; break; 
            case 8: $months_spelled = "agosto"; break; 
            case 9: $months_spelled = "setembro"; break; 
            case 10: $months_spelled = "outubro"; break; 
            case 11: $months_spelled = "novembro"; break; 
            case 12: $months_spelled = "dezembro"; break; 
        }         
         
        return $day . " de " . $months_spelled . " de " . $year . " às " . $hour . ":" . $minute . "." . $second . "\n"; 
    } 
     
    function extract_project( $nome_arquivo ) 
    { 
        list($project) = split("__", $name_file); 
        return $project; 
    }     

    $directory = $_SESSION['diretorio']; 
    $site = $_SESSION['site']; 
     
    if( $directory == "" )
    {
    //    $directory = "teste"; 
          $directory = CELConfig_ReadVar("DAML_dir_relativo_ao_CEL") ;
    }

    if( $site == "" ) 
    {
    //    $site = "http://139.82.24.189/cel_vf/aplicacao/teste/";
          $site = "http://" . CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") . CELConfig_ReadVar("DAML_dir_relativo_ao_CEL") ;
          if ( $site == "http:///" )
          {
             print( "Atenção: O arquivo de configuração do CELConfig (padrão: config2.conf) precisa ser configurado corratamente.<BR>\n * Não foram preenchidas as variáveis 'HTTPD_ip','CEL_dir_relativo' e 'DAML_dir_relativo_ao_CEL'.<BR>\nPor favor, verifique o arquivo e tente novamente.<BR>\n" ) ;
          }
    }
     
    /* Monta a tabela    de arquivos    DAML */ 
    print( "<CENTER><TABLE WIDTH=\"80%\">\n") ; 
    print( "<TR>\n\t<Th><STRONG>Projeto</STRONG></Th>\n\t<Th><STRONG>Gerado em</STRONG></Th>\n</TR>\n" ); 
    if ( $dir_handle = @opendir( $directory )    ) 
    { 
        while ( ( $file = read_directory($dir_handle) ) !== false ) 
        { 
            if ( is_file( $directory . "/" . $file ) && $file != "." && $file != ".." ) 
            { 
                print( "<TR>\n" ); 
                print( "\t<TD WIDTH=\"25%\" CLASS=\"Estilo\"><B>" . extract_project( $file ) . "</B></TD>\n" ); 
                print( "\t<TD WIDTH=\"55%\" CLASS=\"Estilo\">" . extract_date( $file) . "</TD>\n" ); 
                print( "\t<TD WIDTH=\"10%\" >[<A HREF=\"" . $site . $file . "\">Abrir</A>]</TD>\n" ); 
                print( "</TR>\n" ); 
            } 
        } 
        closedir( $dir_handle ); 
    } 
    print("</TABLE></CENTER>\n") ; 
?> 
</BODY> 
</HTML> 