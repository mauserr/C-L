<html>

    <head>
        <title></title>
    </head>

    <body>

        <?php
        /*************************************************************
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

        echo "<br>FIM !!!";

        mysql_close($link);
        ?>

    </body>

</html>