<?php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");

check_User("index.php");


$connect = bd_connect() or die("Erro ao conectar ao SGBD");


if (isset($submit)) {

	$query_delete_sql = "DELETE FROM participa
	WHERE id_usuario != " . $_SESSION['id_usuario_corrente'] . "
	AND id_projeto = " . $_SESSION['id_projeto_corrente'];
	mysql_query($query_delete_sql) or die("Erro ao executar a query de DELETE");

	$number_of_selected_users = count($usuarios);
	 
	for ($i = 0; $i < $number_of_selected_users; $i++) {

		$query_insert_sql = "INSERT INTO participa (id_usuario, id_projeto)
		VALUES (" . $usuarios[$i] . ", " . $_SESSION['id_projeto_corrente'] . ")";
		mysql_query($query_insert_sql) or die("Erro ao cadastrar usuario");
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
        <title>Selecione os usu�rios</title>
        <script language="javascript1.3" src="MSelect.js"></script>
        <script language="javascript1.3">

        function createMSelect() {
            var usr_lselect = document.forms[0].elements['usuarios[]'];
            var usr_rselect = document.forms[0].usuarios_r;
            var usr_l2r = document.forms[0].usr_l2r;
            var usr_r2l = document.forms[0].usr_r2l;
            var MS_usr = new MSelect(usr_lselect, usr_rselect, usr_l2r, usr_r2l);
        }

        function selAll() {
            var usuarios = document.forms[0].elements['usuarios[]'];
            for (var i = 0; i < usuarios.length; i++)
                usuarios.options[i].selected = true;
        }

        </script>
        <style>
        <!--
        select {
            width: 200;
            background-color: #CCFFFF
        }
        -->
        </style>
    </head>
    <body onLoad="createMSelect();">
        <h4>Selecione os usu�rios para participar do projeto "<span style="color: orange"><?=simple_query("nome", "projeto", "id_projeto = " . $_SESSION['id_projeto_corrente'])?></span>":</h4>
        <p style="color: red">Mantenha <strong>CTRL</strong> pressionado para selecionar m�ltiplas op��es</p>
        <form action="" method="post" onSubmit="selAll();">
        <table cellspacing="8" width="100%">
            <tr>
                <td align="center" style="color: green">Participantes:</td>
                <td></td>
                <td></td>
            </tr>
            <tr align="center">
                <td rowspan="2">
                    <select name="usuarios[]" multiple size="6">

<?php

// Scenario - Make a relation User/Project
// Objective: Allows the administrator to make a relationship between project and a new user
	// Actors:    Administrator

		$query_select_sql = "SELECT u.id_usuario, login
		FROM usuario u, participa p
		WHERE u.id_usuario = p.id_usuario
		AND p.id_projeto = " . $_SESSION['id_projeto_corrente'] . "
		AND u.id_usuario != " . $_SESSION['id_usuario_corrente'];

		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
		while ($result = mysql_fetch_array($query_result_sql)) {
?>

                        <option value="<?=$result['id_usuario']?>"><?=$result['login']?></option>

<?php
    }
?>

                    </select>
                </td>
                <td>
                    <input name="usr_l2r" type="button" value="->">
                </td>
                <td rowspan="2">
                    <select  multiple name="usuarios_r" size="6">

<?php
    $alternative_query_select_sql = "SELECT id_usuario FROM participa where participa.id_projeto =".$_SESSION['id_projeto_corrente'];
	$alternative_query_result_sql = mysql_query($alternative_query_result_sql) or die("Erro ao enviar a subquery");
	$alternative_sub_query_result_sql = "(0)";
		if($alternative_sub_query_result_sql != 0)
		{
			$row = mysql_fetch_row($alternative_query_result_sql);
			$alternative_sub_query_result = "( $row[0]";
			while($row = mysql_fetch_row($subqrr))
				$alternative_sub_query_result = "$alternative_sub_query_result, $row[0]";
				$alternative_sub_query_result = "$alternative_sub_query_result )";
		}
		$query_select_sql = "SELECT usuario.id_usuario, usuario.login FROM usuario where usuario.id_usuario not in ".$resultadosubq;

		echo($query_select_sql);
		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
		while ($result = mysql_fetch_array($query_result_sql))
  
?>
                        <option value="<?=$result['id_usuario']?>"><?=$result['login']?></option>

<?php
    }
?>

                    </select>
                </td>
            </tr>
            <tr align="center">
                <td>
                    <input name="usr_r2l" type="button" value="<-">
                </td>
            </tr>
            <tr>
                <td align="center" colspan="3" height="50" valign="bottom"><input name="submit" type="submit" value="Atualizar"></td>
            </tr>
        </table>
        </form>
        <br><i><a href="showSource.php?file=rel_usuario.php">Veja o c�digo fonte!</a></i>
    </body>
</html>

<?php

?>