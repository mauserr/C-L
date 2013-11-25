<?php
/*************************************************************
 * File: _main.php
 * purpose: main functions
 * 
 ************************************************************/
session_start();

require_once 'funcoes_genericas.php'; //("funcoes_genericas.php");
require_once '/Functions/check_User.php';
check_User("index.php");        // Checa se o usuario foi autenticado

?>

<html>
<head>
<script language="javascript1.3">

        // Funcoes que serao usadas quando o script
        // for chamado atraves dele proprio ou da arvore
        function reCarrega(URL) {
            document.location.replace(URL);
        }

        function altCenario(scenario) {
            var url = 'alter_scenario.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_scenario=' + scenario;
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function rmvCenario(scenario) {
            var url = 'remove_scenario.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_scenario=' + scenario;
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function altLexico(lexicon) {
            var url = 'alt_lexico.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_lexicon=' + lexicon;
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function rmvLexico(lexicon) {
            var url = 'remov_lexicon.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_lexicon=' + lexicon;
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        // Funcoes que serao usadas quando o script
        // for chamado atraves da heading.php
        function pedidoCenario() {
            var url = 'ver_pedido_cenario.php?id_project=' + '<?=$id_project?>';
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function pedidoLexico() {
            var url = 'see_Lexicon_Request.php?id_project=' + '<?=$id_project?>';
            var where = '_blank';
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function addUsuario() {
            var url = 'add_usuario.php';
            var where = '_blank';
            var window_spec = 'dependent,height=270,width=490,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function relUsuario() {
            var url = 'rel_usuario.php';
            var where = '_blank';
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }

        function geraXML() {
            var url = 'xml_gerador.php?id_project=' + '<?=$id_project?>';
            var where = '_blank';
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec);
        }
        </script>
<script type="text/javascript" src="mtmtrack.js">
        </script>
</head>
<body>

	<?php

	include("frame_inferior.php");

	if (isset($id) && isset($t)) {      // SCRIPT CHAMADO PELO PROPRIO MAIN.PHP (OU PELA ARVORE)

		if ($t == "c") {
			?>

	<h3>Informações sobre o cenário</h3>

	<?php
		} else {
			?>

	<h3>Informações sobre o léxico</h3>

	<?php
		}
		?>

	<table>

		<?php
		$c = bd_connect() or die("Erro ao conectar ao SGBD");

		if ($t == "c") {        // if its a scenario
			$query = "SELECT id_scenario, title, objective, context, actors, resources, episodes
			FROM scenario
			WHERE id_scenario = $id";
			$query_result = mysql_query($query) or die("Erro ao enviar a query de selecao");
			$result = mysql_fetch_array($query_result);
			?>

		<tr>
			<td>Titulo:</td>
			<td><?=$result['title']?></td>
		</tr>
		<tr>
			<td>Objetivo:</td>
			<td><?=$result['objective']?></td>
		</tr>
		<tr>
			<td>Contexto:</td>
			<td><?=$result['context']?></td>
		</tr>
		<tr>
			<td>Atores:</td>
			<td><?=$result['actors']?></td>
		</tr>
		<tr>
			<td>Recursos:</td>
			<td><?=$result['resouces']?></td>
		</tr>
		<tr>
			<td>Episódios:</td>
			<td><?=$result['episodes']?></td>
		</tr>
		<tr>
			<td height="40" valign="bottom"><a href="#"
				onClick="altCenario(<?=$result['id_scenario']?>);">Alterar Cenï¿½rio</a>
			</td>
			<td valign="bottom"><a href="#"
				onClick="rmvCenario(<?=$result['id_scenario']?>);">Remover Cenï¿½rio</a>
			</td>
		</tr>

		<?php
		} else {
			$query = "SELECT id_lexicon, name, notion, impact
			FROM lexicon
			WHERE id_lexicon = $id";
			$query_result = mysql_query($query) or die("Erro ao enviar a query de selecao");
			$result = mysql_fetch_array($query_result);
			?>

		<tr>
			<td>Nome:</td>
			<td><?=$result['name']?></td>
		</tr>
		<tr>
			<td>Noção:</td>
			<td><?=$result['notion']?></td>
		</tr>
		<tr>
			<td>Impacto:</td>
			<td><?=$result['impact']?></td>
		</tr>
		<tr>
			<td height="40" valign="bottom"><a href="#"
				onClick="altLexico(<?=$result['id_lexicon']?>);">Alterar Lï¿½xico</a>
			</td>
			<td valign="bottom"><a href="#"
				onClick="rmvLexico(<?=$result['id_lexicon']?>);">Remover Lï¿½xico</a>
			</td>
		</tr>

		<?php
		}
		?>

	</table>
	<br>
	<br>
	<br>

	<?php
	if ($t == "c") {
		?>

	<h3>Cenários que referenciam este cenï¿½rio</h3>

	<?php
	} else {
		?>

	<h3>Cenï¿½rios e termos do lï¿½xico que referenciam este termo</h3>

	<?php
	}

	frame_inferior($c, $t, $id);

	} elseif (isset($id_project)) {         // SCRIPT CHAMADO PELO HEADING.PHP

		// Foi passada uma variavel $id_project. Esta variavel deve conter o id de um
		// projeto que o usuario esteja cadastrado. Entretanto, como a passagem eh
		// feita usando JavaScript (no heading.php), devemos checar se este id realmente
		// corresponde a um projeto que o usuario tenha acesso (seguranca).
		check_proj_perm($_SESSION['current_id_user'], $id_project) or die("Permissao negada");

		// Seta uma variavel de sessao correspondente ao projeto atual
		$_SESSION['current_id_project'] = $id_project;
		?>

	<table>
		<tr>
			<td>Projeto:</td>
			<td><?=simple_query("name", "project", "id_project = $id_project")?>
			</td>
		</tr>
		<tr>
			<td>Data de criação:</td>
			<td><?=simple_query("TO_CHAR(data_criacao, 'DD/MM/YY')", "project", "id_project = $id_project")?>
			</td>
		</tr>
		<tr>
			<td>Descrição:</td>
			<td><?=simple_query("description", "project", "id_project = $id_project")?>
			</td>
		</tr>
	</table>

	<?php

	// Verifica se o usuario eh administrador deste projeto
	if (is_admin($_SESSION['current_id_user'], $id_project)) {
		?>

	<br>
	<p>
		<b>Você é um administrador deste projeto</b>
	</p>
	<p>
		<a href="#" onClick="pedidoCenario();">Verificar pedidos de alteração
			de Cenários</a>
	</p>
	<p>
		<a href="#" onClick="pedidoLexico();">Verificar pedidos de alteraï¿½ï¿½o
			de termos do Léxico</a>
	</p>
	<p>
		<a href="#" onClick="addUsuario();">Adicionar usuário (não existente)
			neste projeto</a>
	</p>
	<p>
		<a href="#" onClick="relUsuario();">Relacionar usuários jáexistentes
			com este projeto</a>
	</p>
	<p>
		<a href="#" onClick="geraXML();">Gerar XML deste projeto</a>
	</p>

	<?php
	}
	} else {        // SCRIPT CHAMADO PELO INDEX.PHP
		?>

	<p>Selecione um projeto acima, ou crie um novo projeto.</p>

	<?php
	}
	?>

</body>
</html>

