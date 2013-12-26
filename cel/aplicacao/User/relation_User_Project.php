<?php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");

check_User("index.php");


$connect = bd_connect() or die("Erro ao conectar ao SGBD");

$users = null;
if (isset($_POST['submit'])){

	$query_delete_sql = "DELETE FROM participates
	WHERE id_user != " . $_SESSION['current_id_user'] . "
	AND id_project = " . $_SESSION['current_id_project'];
	mysql_query($query_delete_sql) or die("Erro ao executar a query de DELETE");

	$number_of_selected_users = count($users);
	 
	for ($i = 0; $i < $number_of_selected_users; $i++){

		$query_insert_sql = "INSERT INTO participates (id_user, id_project)
		VALUES (" . $users[$i] . ", " . $_SESSION['current_id_project'] . ")";
		mysql_query($query_insert_sql) or die("Erro ao cadastrar user");
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
        <h4>Selecione os usu�rios para participar do projeto "<span style="color: orange"><?=simple_query("nome", "projeto", "id_project = " . $_SESSION['current_id_project'])?></span>":</h4>
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

		$query_select_sql = "SELECT u.id_user, login
		FROM user u, participates p
		WHERE u.id_user = p.id_user
		AND p.id_project = " . $_SESSION['current_id_project'] . "
		AND u.id_user != " . $_SESSION['current_id_user'];

		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
		while ($result = mysql_fetch_array($query_result_sql)) {
?>

                        <option value="<?=$result['id_user']?>"><?=$result['login']?></option>

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
                $alternative_query_select_sql = "SELECT id_user FROM participates where participates.id_project =".$_SESSION['current_id_project'];
                $alternative_query_result_sql = mysql_query($alternative_query_select_sql) or die("Erro ao enviar a subquery");
                $alternative_sub_query_result_sql = "(0)";
                
		if($alternative_sub_query_result_sql != 0){
                    
			$row = mysql_fetch_row($alternative_query_result_sql);
			$alternative_sub_query_result = "( $row[0]";
                        
			while($row = mysql_fetch_row($alternative_query_result_sql)){
				$alternative_sub_query_result = "$alternative_sub_query_result, $row[0]";
                        }
                        
			$alternative_sub_query_result = "$alternative_sub_query_result )";
		}
                
		$query_select_sql = "SELECT user.id_user, user.login FROM user where user.id_user not in ".$resultadosubq;

		echo($query_select_sql);
                
		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
		while ($result = mysql_fetch_array($query_result_sql))
  
?>
                        <option value="<?=$result['id_user']?>"><?=$result['login']?></option>

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