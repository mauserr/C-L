<?php

/**
@Title: Login

@Objective: Allows the user to enter in the aplication. 

@Actors: user, aplication

@Resources: URL to acess the system ,  login, passwrod, bd.inc, httprequest.inc, $wrong, $url, showSource.php?file=login.php, esqueciSenha.php, add_usuario.php?novo=true 
**/

/** @Episode 1: Start session **/
session_start();

include("bd.inc");

$url = '';
$submit = '';
$login = '';
$password = '';
$wrong = "false";

include("httprequest.inc");

/** @Episodio 2: Conectar o SGBD **/
/** @Restriï¿½ï¿½o: a funï¿½ï¿½o bd_connect definida em bd.inc ï¿½ utilizada **/
/** @Exceï¿½ï¿½o: Erro ao conectar banco de dados **/

$connect = bd_connect() or die("Erro ao conectar ao SGBD");

/** @Episodio 9: Se o formulï¿½rio tiver sido submetido entï¿½o verificar se o login e senha estï¿½o corretos. **/



if ( isset($_POST['submit'])) 
{        
	
	assert($_POSt['password'] != NULL);
	
	$password_cript = md5($_POST['password']);

	assert($password_cript != NULL);
	
	$query_select_sql = "SELECT id_user FROM user WHERE login='$login' AND password='$password_cript'";
    
	assert(query_select_sql != NULL);
	
	$query_result_sql = mysql_query($query_select_sql) or die("Erro ao executar a query");
  
	/** @Episodio 10: Se o login e/ou senha estiverem incorretos entï¿½o retornar a pï¿½gina de login com wrong=true na URL. **/
	if ( !mysql_num_rows($query_result_sql) ) {        

?>
		<script language="javascript1.3">
			document.location.replace('login.php?wrong=true&url=<?=$url?>');
		</script>

<?php

		$wrong = $_GET["wrong"];
		assert ($wrong != NULL);

    } 

	/** @Episodio 11: Se o login e senha estiverem corretos entï¿½oo registrar sessï¿½o para o usuï¿½rio, fechar login.php e abrir aplicaï¿½ï¿½o . **/
	else {

        $row = mysql_fetch_row($query_result_sql);
        
        assert($row[0] != NULL); 
        $id_usuario_corrente = $row[0];

       
        
        $_SESSION['current_id_user'] = "$row[0]";
?>  
		<script language="javascript1.3">
			opener.document.location.replace('<?=$url?>');
			self.close();
		</script>

<?php
    }
} 

/** @Episodio 3: Mostrar o formulï¿½rio de login para usuï¿½rio. **/
else {    
?>

<html>
    <head>
        <title>Entre com seu Login e Senha</title>
    </head>
    <body>

<?php

	/** @Episodio 4: Se wrong = true entao mostrar a mensagem Login ou Senha incorreto . **/
	if ($wrong=="true") {
		?>

		<p style="color: red; font-weight: bold; text-align: center">
		<img src="Images/Logo_CEL.jpg" width="180" height="180"><br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;Login ou Senha Incorreto</p>

		<?php
	} 
	/** @Episodio 5: Se wrong != true então mostrar a mensagem Entre com seu login e senha. **/
	else {
		?>

		<p style="color: green; font-weight: bold; text-align: center">
		<img src="Images/Logo_CEL.jpg" width="100" height="100"><br/><br/>
		&nbsp;&nbsp;&nbsp;&nbsp;Entre com seu Login e Senha:</p>

		<?php
	}
?>

	<form action="?url=<?=$url?>" method="post">
    <div align="center">
    <table cellpadding="5">
      <tr><td>Login:</td><td><input maxlength="32" name="login" size="24" type="text" ></td></tr>
      <tr><td>Senha:</td><td><input maxlength="32" name="password" size="24" type="password" ></td></tr>
      <tr><td height="10"></td></tr>
      <tr><td align="center" colspan="2"><input name="submit" type="submit" value="Entrar"></td></tr>
    </table>

<?php 		/** @Episodio 6: [REGISTER NEW USER] **/ ?>
            <p><a href="add_usuario.php?novo=true">Cadastrar-se</a>&nbsp;&nbsp;

<?php 	    /** @Episodio 7: [REMEMBER PASSWORD] **/ ?>
            <a href="forgot_password.php">Esqueci senha</a></p>
        </div>
        </form>
    </body>

<?php		/** @Episodio 8: [SHOW SOURCE CODE] **/ ?>

	<i><a href="showSource.php?file=login.php">Veja o código fonte!</a></i>    
</html>

<?php
}
?>