<?php
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
$bd_recupera = bd_connect() or die("Erro ao conectar ao SGBD");

//Scenario -  Choose Project
//Objective:  Allow the Administrator to choose a project.
//Context:    The Administrator wants to choose a project.
//            Pre-Condition: Login, Be Administrator
//Actors:     Administrator, User
//Resources:  User registered
//Episodes:   If the User select from the list of projects a project of which he is an administrator,
//            see ADMINISTRATOR CHOOSE PROJECT.
//            Otherwise, see USER CHOOSE PROJECT.

$query = "SELECT * FROM publicacao";
$query_result_sql = mysql_query($q) or die("Erro ao enviar a query de busca");
?>

    <?php
    while ($result = mysql_fetch_row($query_result_sql)) {
        $id_project = $result[0];
        $date = $result[1];
        $version = $result[2];
        $XML = $result[3];

        $querySearchNameProject = "SELECT * FROM projeto WHERE id_projeto = '$id_project'";
        $querySearch = mysql_query($querySearchNameProject) or die("Erro ao enviar a query de busca de projeto");
        $resultName = mysql_fetch_row($querySearch);
        $name_project = $resultName[1];
        ?>
        <table border='0'>

            <tr>

                <th height="29" width="140"><a href="mostrarProjeto.php?id_projeto=<?= $id_project ?>&versao=<?= $version ?>"><?= $name_project ?></a></th>
                <th height="29" width="140">Data: <?= $date ?></th>
                <th height="29" width="100">Versao: <?= $version ?></th>

            </tr>


        </table>

    <?php
}
?>


</body>

</html>