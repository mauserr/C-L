<?php
session_start();


include("../funcoes_genericas.php");
require_once '../Functions/check_User.php';
require_once'../Functions/project_Functions.php';
include("../httprequest.inc");

// Access control scenario
check_User("index.php");


/* * This script is used when a new project request occurs
 * or when a new user sign up in the system
 * */


/**
 * Scenario  -      Register new project
 * Objective:       Let the user register a new project
 * Context:         User wants to include a new project in the database
 * Precondition:    User must be logged in  
 * Actors:          User
 * Resources:       System, project data, database
 * Episodes:        The user clicks in the option 'add project' found in the superior menu.
 *                  The system offers a table for the user to specify the data for the new project,
 *                  like the project name and the discription.
 *                  The user clicks on the button 'insert'.
 *                  The system saves the new project into the database and automatically construct the navigatio
 *                  for this new project.
 * Exception:	    If a project name already exists and belongs or have the participation
 *                  of this user, the system shows an error message.
 * */
// Called thru the button 'submit'
/*
$submit = null;
$name = null;
$description = null;
*/
if (isset($submit)) {
	
    $id_included_project = include_project($name, $description);


   
    if ($id_included_project != -1) {

        $connect = bd_connect() or die("Erro ao conectar ao SGBD");
        $manager = 1;
        
        $id_usuario_corrente = $_SESSION['id_usuario_corrente'];
        $query_add_sql = "INSERT INTO participates (id_user, id_project, manager) VALUES ($id_usuario_corrente, $id_included_project, $manager  )";
        mysql_query($query_add_sql) or die("Erro ao inserir na tabela participa");
        
    } else {
        
        ?>
        
            <html>
            <title>Erro</title>
            <body>
                <p style="color: red; font-weight: bold; text-align: center">Nome de projeto já existente!</p>
            <center><a href="JavaScript:window.history.go(-1)">Voltar</a></center>
        </body>
        </html>   
        <?php
        return;
    }
    ?>

    <script language="javascript1.3">

        self.close();

    </script>

    <?php
} else {
    ?>

    <html>
        <head>
            <title>Adicionar Projeto</title>
            <script language="javascript1.3">

                function checkFormsValues() {
                    if (document.forms[0].name.value == "") {
                        alert('Preencha o campo "Nome"');
                        document.forms[0].name.focus();
                        return false;
                    } else {
                        padrao = /[\\\/\?"<>:|]/;
                        nOK = padrao.exec(document.forms[0].name.value);
                        if (nOK)
                        {
                            window.alert("O nome do projeto nïãoo pode conter nenhum dos seguintes caracteres:   / \\ : ? \" < > |");
                            document.forms[0].name.focus();
                            return false;
                        }
                    }
                    return true;
                }

            </script>
        </head>
        <body>
            <h4>Adicionar Projeto:</h4>
            <br>
            <form action="" method="post" onSubmit="return checkFormsValues();">
                <table>
                    <tr>
                        <td>Nome:</td>
                        <td><input maxlength="128" name="name" size="48" type="text"></td>
                    </tr>
                    <tr>
                        <td>Descrição:</td>
                        <td><textarea cols="48" name="description" rows="4"></textarea></td>
                    <tr>
                        <td align="center" colspan="2" height="60"><input name="submit" type="submit" value="Adicionar Projeto"></td>
                    </tr>
                </table>
            </form>
            <br><i><a href="showSource.php?file=add_project.php">Veja o código fonte!</a></i>
        </body>
    </html>

    <?php
}
?>
