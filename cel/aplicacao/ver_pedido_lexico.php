<?php
// ver_pedido_lexico.php: This script shows the orders refered to the lexicon.
// The manager has the option to see all the orders already validated.
// The manager will also be able to validate and process the orders.
// The manager has a third option, to remove a validated order
// or not from the orders list. The manager can answer to a order via email directly from this page.


session_start();

include("funcoes_genericas.php");
require_once '/Functions/lexicon_functions';
include("httprequest.inc");

// Checa se o usuario foi autenticado
chkUser("index.php");
$submit = null;
$orders = null;
$remove = null;
$id_projeto = null;

if (isset($submit)) {

    $DB = new PGDB ();
    
    $select = new QUERY($DB);
    $update = new QUERY($DB);
    $delete = new QUERY($DB);
    
    for ($count = 0; $count < sizeof($orders); $count++) {
        
        $update->execute("update pedidolex set aprovado= 1 where id_pedido = $orders[$count]");
        tratarPedidoLexico($orders[$count]);
    }
    
    for ($count = 0; $count < sizeof($remove); $count++) {
        
        $delete->execute("delete from pedidolex where id_pedido  = $remove[$count]");
        $delete->execute("delete from sinonimo where id_pedidolex = $remove[$count]");
    }
    ?>

    <script language="javascript1.2">
        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace("main.php");
    </script>
    <h4>Opera��o efetuada com sucesso!</h4>
    <script language="javascript1.2">
        self.close();
    </script>
    <?php
} else {
    ?>
    <html>
        <head>
            <title>Pedido L�xico</title>
        </head>
        <body>
            <h2>Pedidos de Altera��o no L�xico</h2>
            <form action="?id_projeto=<?= $id_projeto ?>" method="post">

    <?php
// Scenario - Verify alteration orders of the lexicon terms
// Objective:	Allows the adminstrator to manage the modification orders of the lexicon terms.
// Context:	Manager wants to see the modification orders of the lexicon terms.
// Preconditions: Login, registered project.
// Actors:	Administrator
// Resources:	System, database.
// Episodes:  1- The adminstrator clicks on the option 'Verificar pedidos de alteração'.
// Restrictions: Only the adminstrator of the project can have this funcionality visible.
//           2- The system provides to the administrator a screen wherer he can see the history
//              of all the pending modifications or not.
//           3- For new inclusion or modification orders of lexicon terms,
//              The system allows the administrator to choose to Aprove or Disaprove.
//           4- For the inclusio or modification orders already aproved,
//              the system only enables the 'Remove' option to the administrator.
//           5- To finish the selection os aprovals e removals, the administrator must click on 'Processar'.

    $DB = new PGDB ();
    $select = new QUERY($DB);
    $select2 = new QUERY($DB);
    $select3 = new QUERY($DB);
    
    $select->execute("SELECT * FROM pedidolex where id_projeto = $id_projeto");
    
    if ($select->getntuples() == 0) {
       
        echo "<BR>Nenhum pedido.<BR>";
        
    } else {
        
        $i = 0;
        
        $record = $select->gofirst();
        while ($record != 'LAST_RECORD_REACHED') {
            
            $id_usuario = $record['id_usuario'];
            $id_pedido = $record['id_pedido'];
            $order_type = $record['tipo_pedido'];
            $aprovado = $record['aprovado'];

            //Catches the synonyms
            $select3->execute("SELECT nome FROM sinonimo WHERE id_pedidolex = $id_pedido");

            $select2->execute("SELECT * FROM usuario WHERE id_usuario = $id_usuario");
            $user = $select2->gofirst();
            
            if (strcasecmp($order_type, 'remover')) {
                ?>
                            <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o l�xico <font color="#ff0000"><?= $record['nome'] ?></font> <? if (!strcasecmp($order_type, 'alterar')) {
                    echo"para l�xico abaixo:</h3>";
                } else {
                    echo"</h3>";
                } ?>
                                <table>
                                    <td><b>Nome:</b></td>
                                    <td><?= $record['nome'] ?></td>

                                    <tr>

                                        <td><b>No��o:</b></td>
                                        <td><?= $record['nocao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Impacto:</b></td>
                                        <td><?= $record['impacto'] ?></td>
                                    </tr>


                                    <tr>
                                        <td><b>Sin�nimos:</b></td>
                                        <td>
                                            <?php
                                            $sinonimo = $select3->gofirst();
                                            $strSinonimos = "";
                                            while ($sinonimo != 'LAST_RECORD_REACHED') {
                                                //echo($sinonimo["nome"] . ", ");
                                                $strSinonimos = $strSinonimos . $sinonimo["nome"] . ", ";
                                                $sinonimo = $select3->gonext();
                                            }

                                            echo(substr($strSinonimos, 0, strrpos($strSinonimos, ",")));
                                            ?>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td><b>Justificativa:</b></td>
                                        <td><textarea name="justificativa" cols="48" rows="2"><?= $record['justificativa'] ?></textarea></td>
                                    </tr>
                                </table>
                                <?php } else {
                                ?>
                                <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o l�xico <font color="#ff0000"><?= $record['nome'] ?></font></h3>
                            <?php
                            }
                            if ($aprovado == 1) {
                                echo "[<font color=\"#ff0000\"><STRONG>Aprovado</STRONG></font>]<BR>";
                            } else {
                                echo "[<input type=\"checkbox\" name=\"pedidos[]\" value=\"$id_pedido\"> <STRONG>Aprovar</STRONG>]<BR>  ";
//                     echo "Rejeitar<input type=\"checkbox\" name=\"remover[]\" value=\"$id_pedido\">" ;
                            }
                            echo "[<input type=\"checkbox\" name=\"remover[]\" value=\"$id_pedido\"> <STRONG>Remover da lista</STRONG>]";
                            print( "<br>\n<hr color=\"#000000\"><br>\n");
                            $record = $select->gonext();
                        }
                    }
                    ?>
                    <input name="submit" type="submit" value="Processar">
                    </form>
                    <br><i><a href="showSource.php?file=ver_pedido_lexico.php">Veja o c�digo fonte!</a></i>
                    </body>
                    </html>

                    <?php
                }
                ?>


