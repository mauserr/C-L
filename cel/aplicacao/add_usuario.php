<?php
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
include("funcoes_genericas.php");
include_once("bd.inc");

$first_try = "true";

include("httprequest.inc");
$name = null;
$email = null;
$login = null;
$password = null;
$psw_conf = null;
if (isset($_POST['submit'])) { 
    
    $first_try = "false";
    $name = $_POST['name'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $psw_conf = $_POST['psw_conf'];
    
    // ** Scenario "Independente Independent user inclusion" **
    // The system checks if all the fields are filled. If some isn't, the
    // system warns the user that all the fields must be filled.

    if ($name == "" || $email == "" || $login == "" || $password == "" || $psw_conf == "") {
        
        $p_style = "color: red; font-weight: bold";
        $p_text = "Por favor, preencha todos os campos.";
        recarrega("?p_style=$p_style&p_text=$p_text&name=$name&email=$email&login=$login&password=$password&senha_conf=$psw_conf&novo=$novo");
    
        
    } else {

        // Test if both passwords provided by the user are the same.
        if ($password != $psw_conf) {
            
            $p_style = "color: red; font-weight: bold";
            $p_text = "Senhas diferentes. Favor preencher novamente as senhas.";
            recarrega("?p_style=$p_style&p_text=$p_text&name=$name&email=$email&login=$login&novo=$novo");
        
            
        } else {

            // ** Scenario "Independente Independent user inclusion" **
            // All the fields are filled. The system now must check if the login provided
            // by the user isnt bein used already.
            // Scenario - Include independent user 
            // Objective: Allow a user, that isnt an administrator, to register
            //            an administrator profile
            // Context:   System open, User wants to register to the system as an administrator.
            //            User in the registration screen
            // Precondition: User must have access to the system.
            // Actors:    User, System
            // Resources:  Interface, Database
            // Episodes:  The system returns to the user an interface with the fields to input
            //            a name, email, login, a password and the confirmation password.
            //            The user fills the fields and clicks in 'Cadastrar'
            //            The system, then, checks if all the fields are filled.
            //              In case that some field isnt filled, the sistem warns that
            //              all the fields must br filled.
            //              In case that all the fields are filled, the system checks in the database
            //              if the chosen login is available
            //              In case of the login isnt available, the system returns to the same screen
            //              with a message for the user to choose another login.

            $connect_bd = bd_connect() or die("Erro ao conectar ao SGBD");
            $query_sql = "SELECT id_user FROM user WHERE login = '$login'";
            $query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query");
            
            
            if (mysql_num_rows($query_result_sql)) {
                
                //                $p_style = "color: red; font-weight: bold";
                //                $p_text = "Login j� existente no sistema. Favor escolher outro login.";
                //                recarrega("?p_style=$p_style&p_text=$p_text&name=$name&email=$email&senha=$password&senha_conf=$psw_conf&novo=$novo");
                
                // Scenario - Add user.
                // Objective: Allows to the administrator to add new users.
                // Context:   The administrator wants to add new usersc
                //            creating new users to the selected project.
                // Preconditions: Login
                // Actors:    Administrator
                // Resources:  User data
                // Episodes:  The administrator clicks in the link 'Adicionar usuario' in this project,
                //            entering the new users information.
                //            In case the login already exists, shows an error message
                //            saying that the login already exists
                ?>
                <script language="JavaScript">
                    alert("Login j� existente no sistema. Favor escolher outro login.")
                </script>

                <?php
                recarrega("?novo=$novo");
            
            // The registration passed through all the tests -- Can now be included to the database
            } else {    
                
                // Replaces all the '<' and '>' for " " 
                $name = str_replace(">", " ", str_replace("<", " ", $name));
                $login = str_replace(">", " ", str_replace("<", " ", $login));
                $email = str_replace(">", " ", str_replace("<", " ", $email));

                // Encrypting the password
                $password = md5($password);
                $query_add_sql = "INSERT INTO user (name, login, email, password) VALUES ('$name', '$login', '$email', '$password')";
                mysql_query($query_add_sql) or die("Erro ao cadastrar o usuario");
                recarrega("?cadastrado=&novo=$novo&login=$login");
            }
        }   // else
    }   // else
} elseif (isset($cadastrado)) {

    // Registration completed. Depending of where the user came from,
    // should send him to a diferent place.

    
    // Came from the initial loggin screen
    if ($novo == "true") {      
        // ** Scenario "Independente Independent user inclusion" **
        // The user have just registered to the system, now he must be
        // redirected to the projects inclusion screen
        // Registers that the user is logged with a recently registered login
        // Scenario - Independent user registration
        // Objective: Allow a user, that isnt an administrator, to register
        //            an administrator profile
        // Context:   System open, User wants to register to the system as an administrator.
        //            User in the registration screen
        // Contexto:  Sistema aberto Usu�rio deseja cadastrar-se ao sistema como administrador.
        // Precondition: User must have access to the system.
        // Actors:    User, System
        // Resources:  Interface, Database
        // Episodes:  In case that the chosen login doesnt exists, the system registers the user
        //               as an administrator in the database
       // $id_usuario_corrente = simple_query("id_usuario", "usuario", "login = '$login'");
       // session_register("id_usuario_corrente");
        $_SESSION['current_id_user'] = simple_query("id_usuario", "usuario", "login = '$login'");
        ?>

        <script language="javascript1.3">

        // Redireciona o usuario para a parte de inclusao de projetos
            opener.location.replace('index.php');
            open('add_projeto.php', '', 'dependent,height=300,width=550,resizable,scrollbars,titlebar');
            self.close();


        </script>

        <?php
    } else {

        // ** Scenario "Editing the user" **
        // The administrator of the project just included the user.
        // Now should add the user included to the project
        // of the administrator.
       

        // Conexion with the database
        $connect_bd = bd_connect() or die("Erro ao conectar ao SGBD");
        
        
        // $login is the login of the included user, passed through the URL
        $id_usuario_incluido = simple_query("id_user", "user", "login = '$login'");
        
        $insert_sql = "INSERT INTO participates (id_user, id_project)
		VALUES ($id_usuario_incluido, " . $_SESSION['current_id_project'] . ")";
        
        mysql_query($insert_sql) or die("Erro ao inserir na tabela participa");

        $nome_usuario = simple_query("name", "user", "id_user = $id_usuario_incluido");
        $nome_projeto = simple_query("name", "project", "id_project = " . $_SESSION['current_id_project']);
        ?>

        <script language="javascript1.3">

            document.writeln('<p style="color: blue; font-weight: bold; text-align: center">Usu�rio <b><?= $nome_usuario ?></b> cadastrado e inclu�do no projeto <b><?= $nome_projeto ?></b></p>');
            document.writeln('<p align="center"><a href="javascript:self.close();">Fechar</a></p>');

        </script>

        <?php
    }
} else {   
    if (empty($p_style)) {
        $p_style = "color: green; font-weight: bold";
        $p_text = "Favor preencher os dados abaixo:";
    }

    if (true) {
        $email = "";
        $login = "";
        $name = "";
        $password = "";
        $psw_conf = "";
    }
    ?>

    <html>
        <head>
            <title>Cadastro de Usuário</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        </head>
        <body>
            <script language="JavaScript">
                <!--
            function verifyEmail(form)
                {
                    email = form.email.value;
                    
                    //verify if the email contains a @
                    i = email.indexOf("@");
                    if (i == -1)
                    {
                        alert('Aten��o: o E-mail digitado n�o � v�lido.');
                        return false;
                    }
                }

                function checkEmail(email) {
                    if (email.value.length > 0)
                    {
                        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.value))
                        {
                            return (true)
                        }
                        alert("Aten��o: o E-mail digitado não é válido.");
                        email.focus();
                        email.select();
                        return (false);
                    }
                }

                //-->



            </SCRIPT>

            <p style="<?= $p_style ?>">
    <?= $p_text ?>
            </p>
            <form action="?novo=<?= $novo ?>" method="post">
                <table>
                    <tr>
                        <td>Nome:</td>
                        <td colspan="3"><input name="name" maxlength="255" size="48"
                                               type="text" value=""></td>
                    </tr>
                    <tr>
                        <td>E-mail:</td>
                        <td colspan="3"><input name="email" maxlength="64" size="48"
                                               type="text" value="" OnBlur="checkEmail(this);"></td>
                    </tr>
                    <tr>
                        <td>Login:</td>
                        <td><input name="login" maxlength="32" size="24" type="text"
                                   value=""></td>
                    </tr>
                    <tr>
                        <td>Senha:</td>
                        <td><input name="password" maxlength="32" size="16" type="password"
                                   value=""></td>
                        <td>Senha (confirmacao):</td>
                        <td><input name="psw_conf" maxlength="32" size="16"
                                   type="password" value=""></td>
                    </tr>
                    <tr>

    <?php
    // Scenario - Add user
    // Objectivr: Allows the administrator to add new users.
    // Context:   The administrator wants to add new users
    //             to the selected project.
    // Preconditions: Login
    // Actors:    Administrator
    // Resources:  User data
    // Episodes: Clicking on the button 'Cadastrar' to confirm the adicion of the new user
    //             to the selected project.
    //            The new user receives a message in his email with the login and password.
    ?>

                        <td align="center" colspan="4" height="40" valign="bottom"><input
                                name="submit" onClick="return verifyEmail(this.form);"
                                type="submit" value="Cadastrar"></td>
                    </tr>
                </table>
            </form>
            <br>
            <i><a href="showSource.php?file=add_usuario.php">Veja o c�digo fonte!</a>
            </i>
        </body>
    </html>

                        <?php
                    }
                    ?>