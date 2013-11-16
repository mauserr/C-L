<?php
session_start();

require_once '../Functions/check_User.php';
require_once'../Functions/project_Functions.php';
include("../httprequest.inc");
require_once '../funcoes_genericas.php';

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
if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
	
	assert($submit != NULL);
	assert($id_user != NULL);
	assert($id_project != NULL);
	assert($id_user < 0);
	assert($id_project < 0);
	
    $id_included_project = include_project($name, $description);
	

   
    if ($id_included_project != -1) {

        $connect = bd_connect() or die("Erro ao conectar ao SGBD");
        $manager = 1;
        
        $current_id_user= $_SESSION['current_id_user'];
        $query_add_sql = "INSERT INTO participates (id_user, id_project, manager) VALUES ($current_id_user, $id_included_project, $manager  )";
        mysql_query($query_add_sql) or die("Erro ao inserir na tabela participa");
        
    } else {
        
        ?>
        
            <html>
            <title>Erro</title>
            <body>
                <p style="color: red; font-weight: bold; text-align: center">Nome de projeto j� existente!</p>
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
                            window.alert("O nome do projeto n�o pode conter nenhum dos seguintes caracteres:   / \\ : ? \" < > |");
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
                        <td>Descri��o:</td>
                        <td><textarea cols="48" name="description" rows="4"></textarea></td>
                    <tr>
                        <td align="center" colspan="2" height="60"><input name="submit" type="submit" value="Adicionar Projeto"></td>
                    </tr>
                </table>
            </form>
            <br><i><a href="showSource.php?file=add_project.php">Veja o c�digo fonte!</a></i>
        </body>
    </html>

    <?php
}
?>
