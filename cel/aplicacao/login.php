<?php

/**
@Titulo: Acessar o sistema

@Objetivo: Permitir que o usuï¿½rio acesse a Aplicaï¿½ï¿½o de Ediï¿½ï¿½o de Lï¿½xicos e de Ediï¿½ï¿½o de Cenï¿½rios, cadastre-se no sistema ou requisite sua senha no caso de tï¿½-la esquecido.

@Contexto: A pï¿½gina da aplicaï¿½ï¿½o ï¿½ acessada. Na pï¿½gina de abertura ../cel/aplicacao/login.php o usuï¿½rio insere login ou senha incorretos - $wrong=true.

@Atores: usuï¿½rio, aplicaï¿½ï¿½o

@Recursos: URL de acesso ao sistema,  login, senha, bd.inc, httprequest.inc, $wrong, $url, showSource.php?file=login.php, esqueciSenha.php, add_usuario.php?novo=true 
**/

/** @Episodio 1: Iniciar sessï¿½o **/
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
if ( $submit == 'Entrar') 
{        
	$password_cript = md5($password);
	$query_select_sql = "SELECT id_user FROM user WHERE login='$login' AND password='$password'";
    $query_result_sql = mysql_query($query_select_sql) or die("Erro ao executar a query");
  
	/** @Episodio 10: Se o login e/ou senha estiverem incorretos entï¿½o retornar a pï¿½gina de login com wrong=true na URL. **/
	if ( !mysql_num_rows($query_result_sql) ) {        

?>
		<script language="javascript1.3">
			document.location.replace('login.php?wrong=true&url=<?=$url?>');
		</script>

<?php

		$wrong = $_get["wrong"];
		
    } 

	/** @Episodio 11: Se o login e senha estiverem corretos entãoo registrar sessï¿½o para o usuï¿½rio, fechar login.php e abrir aplicaï¿½ï¿½o . **/
	else {

        $row = mysql_fetch_row($query_result_sql);
       // $id_usuario_corrente = $row[0];

        //session_register("id_usuario_corrente");
        $_SESSION['id_usuario_corrente'] = $row[0];
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
	/** @Episodio 5: Se wrong != true entï¿½o mostrar a mensagem Entre com seu login e senha. **/
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
      <tr><td>Login:</td><td><input maxlength="32" name="login" size="24" type="text"></td></tr>
      <tr><td>Senha:</td><td><input maxlength="32" name="password" size="24" type="password"></td></tr>
      <tr><td height="10"></td></tr>
      <tr><td align="center" colspan="2"><input name="submit" type="submit" value="Entrar"></td></tr>
    </table>

<?php 		/** @Episodio 6: [CADASTRAR NOVO USUï¿½RIO] **/ ?>
            <p><a href="add_usuario.php?novo=true">Cadastrar-se</a>&nbsp;&nbsp;

<?php 	    /** @Episodio 7: [LEMBRAR SENHA] **/ ?>
            <a href="forgot_password.php">Esqueci senha</a></p>
        </div>
        </form>
    </body>

<?php		/** @Episodio 8: [MOSTRAR O Cï¿½DIGO FONTE] **/ ?>

	<i><a href="showSource.php?file=login.php">Veja o código fonte!</a></i>    
</html>

<?php
}
?>