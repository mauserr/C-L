<html>

    <head>
        <title></title>
    </head>

    <body>

        <?php
        /*         * ***********************************************************
         * File: create.php
         * purpose: Checks if the user is autenticated. If so, keeps running
         * the program. Otherwise, it will force a logon window.
         * 
         * ********************************************************** */
        include_once( "bd.inc" );
        include 'auxiliar_bd.php';

        $link = bd_connect() or die("Erro na conex�o � BD : " . mysql_error());


        $query = "show tables";
        $result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);


        while ($line = mysql_fetch_array($result, MYSQL_BOTH)) {
            $tabela = "show create table cel." . $line[0];
            $atributos = mysql_query($tabela) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
            while ($linha = mysql_fetch_array($atributos, MYSQL_BOTH)) {
                print ("\$query = \"$linha[1] ;\";<br>");
                print ("\$result = mysql_query(\$query) or die(\"A consulta � BD falhou : \" . mysql_error() . __LINE__);<br>");
                print ("<br>");
            }
        }

        /*
          $query = "alter table lexico add impacto text";
          $result = mysql_query($query) or die("A cria��o de id_projeto falhou : " . mysql_error() . __LINE__);
         */
        /*
          $query = "alter table pedidolex add tipo varchar(20)";
          $result = mysql_query($query) or die("A cria��o de id_projeto falhou : " . mysql_error() . __LINE__);
         */
        /*
          $query = "delete from lexico where nome = 'teste3'";
          $result = mysql_query($query) or die("A cria��o de id_projeto falhou : " . mysql_error() . __LINE__);
         */

        /*
          $query = "CREATE TABLE `pedidocon` ( `id_pedido` int(11) NOT NULL auto_increment,
          `id_usuario` int(11) NOT NULL default '0',
          `id_projeto` int(11) NOT NULL default '0',
          `tipo_pedido` varchar(7) NOT NULL default '',
          `aprovado` int(1) NOT NULL default '0',
          `id_conceito` int(11) default NULL,
          `nome` varchar(255) NOT NULL default '',
          `descricao` text NOT NULL,
          `namespace` text NOT NULL,
          `justificativa` text,
          `id_status` int(1) default NULL,
          PRIMARY KEY (`id_pedido`) ) TYPE=MyISAM ;";
          $result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
         */
        /*
          $query = "CREATE TABLE `pedidorel` ( `id_pedido` int(11) NOT NULL auto_increment,
          `id_usuario` int(11) NOT NULL default '0',
          `id_projeto` int(11) NOT NULL default '0',
          `tipo_pedido` varchar(7) NOT NULL default '',
          `aprovado` int(1) NOT NULL default '0',
          `id_relacao` int(11) default NULL,
          `nome` varchar(255) NOT NULL default '',
          `justificativa` text,
          `id_status` int(1) default NULL,
          PRIMARY KEY (`id_pedido`) ) TYPE=MyISAM ;";
          $result = mysql_query($query) or die("A consulta � BD falhou : " . mysql_error() . __LINE__);
         */
        echo "<br>FIM !!!";


        mysql_close($link);
        ?>

    </body>

</html>