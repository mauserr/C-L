<?php
// ver_pedido_lexico.php: This script shows the orders refered to the lexicon.
// The manager has the option to see all the orders already validated.
// The manager will also be able to validate and process the orders.
// The manager has a third option, to remove a validated order
// or not from the orders list. The manager can answer to a order via email directly from this page.


session_start();

include("funcoes_genericas.php");
require_once '/Functions/lexicon_functions.php';
require_once '/Functions/check_User.php';
include("httprequest.inc");

// Checa se o usuario foi autenticado
check_User("index.php");
$submit = null;
$orders = null;
$remove = null;
$id_project = null;

if (isset($submit)) {

    $DB = new PGDB ();
    
    $select = new QUERY($DB);
    $update = new QUERY($DB);
    $delete = new QUERY($DB);
    
    for ($count = 0; $count < sizeof($orders); $count++) {
        
        $update->execute("update request_lexicon set aproved= 1 where id_request = '$orders[$count]'");
        tratarPedidoLexico($orders[$count]);
    }
    
    for ($count = 0; $count < sizeof($remove); $count++) {
        
        $delete->execute("delete from pedidolex where id_pedido  = '$remove[$count]'");
        $delete->execute("delete from sinonimo where id_pedidolex = '$remove[$count]'");
    }
    ?>

    <script language="javascript1.2">
        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace("main.php");
    </script>
    <h4>Operação efetuada com sucesso!</h4>
    <script language="javascript1.2">
        self.close();
    </script>
    <?php
} else {
    ?>
    <html>
        <head>
            <title>Pedido Léxico</title>
        </head>
        <body>
            <h2>Pedidos de Alteração no Léxico</h2>
            <form action="?id_project=<?= $id_project ?>" method="post">

    <?php
// Scenario - Verify alteration orders of the lexicon terms
// Objective:	Allows the adminstrator to manage the modification orders of the lexicon terms.
// Context:	Manager wants to see the modification orders of the lexicon terms.
// Preconditions: Login, registered project.
// Actors:	Administrator
// Resources:	System, database.
// Episodes:  1- The adminstrator clicks on the option 'Verificar pedidos de alteraÃ§Ã£o'.
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
    
    $select->execute("SELECT * FROM request_lexicon where id_project = '$id_project'");
    
    if ($select->getntuples() == 0) {
       
        echo "<BR>Nenhum pedido.<BR>";
        
    } else {
        
        $i = 0;
        
        $record = $select->gofirst();
        while ($record != 'LAST_RECORD_REACHED') {
            
            $id_user = $record['id_user'];
            $id_request = $record['id_request'];
            $order_type = $record['type_request'];
            $aproved = $record['aproved'];

            assert($id_user !=NULL);
            assert($id_request !=NULL);
            assert($order_type !=NULL);
            assert($aproved !=NULL);
            
            //Catches the synonyms
            $select3->execute("SELECT name FROM synonym WHERE id_request_lexicon = '$id_request'");

            $select2->execute("SELECT * FROM user WHERE id_user = '$id_user'");
            $user = $select2->gofirst();
            
            if (strcasecmp($order_type, 'remover')) {
                ?>
                            <h3>O usuáio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['name'] ?></a> pede para <?= $order_type ?> o léxico <font color="#ff0000"><?= $record['name'] ?></font> <? if (!strcasecmp($order_type, 'alter')) {
                    echo"para léxico abaixo:</h3>";
                } else {
                    echo"</h3>";
                } ?>
                                <table>
                                    <td><b>Nome:</b></td>
                                    <td><?= $record['name'] ?></td>

                                    <tr>

                                        <td><b>Noção:</b></td>
                                        <td><?= $record['notion'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Impacto:</b></td>
                                        <td><?= $record['impact'] ?></td>
                                    </tr>


                                    <tr>
                                        <td><b>Sinônimos:</b></td>
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
                                        <td><textarea name="justificativa" cols="48" rows="2"><?= $record['justificative'] ?></textarea></td>
                                    </tr>
                                </table>
                                <?php } else {
                                ?>
                                <h3>O usuï¿½rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['name'] ?></a> pede para <?= $order_type ?> o léxico <font color="#ff0000"><?= $record['name'] ?></font></h3>
                            <?php
                            }
                            if ($aproved == 1) {
                                echo "[<font color=\"#ff0000\"><STRONG>Aprovado</STRONG></font>]<BR>";
                            } else {
                                echo "[<input type=\"checkbox\" name=\"pedidos[]\" value=\"$id_request\"> <STRONG>Aprovar</STRONG>]<BR>  ";
//                     echo "Rejeitar<input type=\"checkbox\" name=\"remover[]\" value=\"$id_pedido\">" ;
                            }
                            echo "[<input type=\"checkbox\" name=\"remover[]\" value=\"$id_request\"> <STRONG>Remover da lista</STRONG>]";
                            print( "<br>\n<hr color=\"#000000\"><br>\n");
                            $record = $select->gonext();
                        }
                    }
                    ?>
                    <input name="submit" type="submit" value="Processar">
                    </form>
                    <br><i><a href="showSource.php?file=ver_pedido_lexico.php">Veja o código fonte!</a></i>
                    </body>
                    </html>

                    <?php
                }
                ?>


