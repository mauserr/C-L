<?php
/* * ***********************************************************
 * File: projetos.php
 * purpose: Main file for loading project data
 * 
 * ********************************************************** */

include("funcoes_genericas.php");
?>
<html>

    <head>
    <p style="color: red; font-weight: bold; text-align: center">
        <img src="Images/Logo_CEL.jpg" width="180" height="100"><br/><br/>
        Projetos Publicados</p>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

    <?php
    $connect_db = bd_connect() or die("Erro ao conectar ao SGBD");

//Scenario -  Choose Project
//Objective:  Allow the Administrator to choose a project.
//Context:    The Administrator wants to choose a project.
//            Pre-Condition: Login, Be Administrator
//Actors:     Administrator, User
//Resources:  User registered
//Episodes:   If the User select from the list of projects a project of which he is an administrator,
//            see ADMINISTRATOR CHOOSE PROJECT.
//            Otherwise, see USER CHOOSE PROJECT.

    $id_project = null;
    $date = null;
    $version = null;
    $XML = null;
    $name_project = null;
    
    $query = "SELECT * FROM publication";
    $query_result_sql = mysql_query($query) or die("Erro ao enviar a query de busca");

    while ($result = mysql_fetch_row($query_result_sql)) {
        $id_project = $result[0];
        $date = $result[1];
        $version = $result[2];
        $XML = $result[3];

        $query_search_name_project = "SELECT * FROM project WHERE id_project = '$id_project'";
        $query_search = mysql_query($query_search_name_project) or die("Erro ao enviar a query de busca de projeto");
        $result_name = mysql_fetch_row($query_search);
        $name_project = $result_name[1];
        ?>

        <table border='0'>

            <tr>

                <th height="29" width="140"><a href="mostrarProjeto.php?id_project=<?= $id_project ?>&version=<?= $version ?>"><?= $name_project ?></a></th>
                <th height="29" width="140">Data: <?= $date ?></th>
                <th height="29" width="100">Versao: <?= $version ?></th>

            </tr>

        </table>

        <?php
    }
    ?>

</body>

</html>