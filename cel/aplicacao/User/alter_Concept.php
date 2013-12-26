<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

// alt_conceito.php: This script makes a change request of a project concept.
// The user receive a 'form' with the current concept
// and can make changes in all the fields, except the name. At the and of the main window
// it returns to the first window and the tree is closed. The change form is closed too.
// File name: main.php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");
include_once("bd.inc");

// Checks if the user was authenticated
check_User("index.php");


// Connects to the database
$connect = bd_connect() or die("Erro ao conectar ao SGBD");

// Script called thru the forms submit
if (isset($_POST['submit'])){
    
    $id_concept = $_POST['id_concept'];
    inserirPedidoAlterarConceito($_SESSION['current_id_project'], 
			
    $_POST['id_concept'], 
    $_POST['name'], 
    $_POST['description'], 
    $_POST['namespace'], 
    $_POST['justification'], 
    $_SESSION['current_id_user']);
    
    
    ?>

    <script language="javascript1.3">

        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace('main.php?id_project=<?= $_SESSION['current_id_project'] ?>');

    </script>

    <h4>Opera��o efetuada com sucesso!</h4>

    <script language="javascript1.3">

        self.close();

    </script>

    <?php
}else{ // Script chamado atraves do link no cenario corrente
    $project_name = simple_query("name", "project", "id_project = " . $_SESSION['current_id_project']);

    $query_select = "SELECT * FROM concept WHERE id_concept = $id_concept";
    $query = mysql_query($query_select) or die("Erro ao executar a query");
    $result = mysql_fetch_array($query);

// Scenario -    Change concept 
//Objective:   	Allows a user to change a scenario.
//Context:      The user wants to change the concept previously registered.
//Precondition: Login, Scenario must be registered in the system
//Actors:	User
//Resources:	System, registered data
//Episodes:	The system shows to the user the same screen from 'Incluir Cenario'
//              But with all the fields filled with the selected scenario information
//              and editables in each os it's respective fields.
//              The fields 'Projeto' e 'Titulo' must be filled but not editable.
//              Will be shown a field 'Justificativa' for the user to put an explanation
//              for the changes made.
    ?>

    <html>
        <head>
            <title>Alterar Conceito</title>
        </head>
        <body>
            <h4>Alterar Conceito</h4>
            <br>
            <form action="?id_project=<?= $id_project ?>" method="post">
                <table>
                    <tr>
                        <td>Projeto:</td>
                        <td><input disabled size="48" type="text" value="<?= $project_name ?>"></td>
                    </tr>
                    <input type="hidden" name="id_conceitos" value="<?= $result['id_concept'] ?>">
                    <td>Nome:</td>
    <? $result['name'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['name']); ?>
                    <input type="hidden" name="name" value="<?= $result['name'] ?>">
                    <td><input disabled maxlength="128" name="nome2" size="48" type="text" value="<?= $result['name'] ?>"></td>
                    <tr>
                        <td>Descricao:</td>
    <? $result['descricao'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['descricao']); ?>

                        <td><textarea name="descricao" cols="48" rows="3"><?= $result['descricao'] ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Namespace:</td>
    <? $result['namespace'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['namespace']); ?>
                        <td><textarea name="namespace" cols="48" rows="3"><?= $result['namespace'] ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Justificativa para a altera&ccedil;&atilde;o:</td>
                        <td><textarea name="justificativa" cols="48" rows="2"></textarea></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2" height="60"><input name="submit" type="submit" value="Alterar Cen�rio" onClick="updateOpener()"></td>
                    </tr>
                </table>
            </form>
            <br><i><a href="showSource.php?file=alt_cenario.php">Veja o c�digo fonte!</a></i>
        </body>
    </html>

    <?php
}
?>
