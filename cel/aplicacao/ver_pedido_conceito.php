<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// ver_pedido_conceito.php: This script shows all the orders refered to the conpect.
// The manager has the option to see the orders already validated.
// The manager can also validate and process the orders.
// The manager will have a third option, to remove the order
// validated or not from the orders list. The manager can answer
// to an order via email directly from this page.

session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
require_once '/Functions/concept_Functions.php';


chkUser("index.php");
$submit = null;
$orders = null;
$remove = null;
$id_project = null;
$aproved = null;
$id_request = null;

if (isset($submit)) {
    $DB = new PGDB ();
    $select = new QUERY($DB);
    $update = new QUERY($DB);
    $delete = new QUERY($DB);
    for ($count = 0; $count < sizeof($orders); $count++) {
        $update->execute("update pedidocon set aproved= 1 where id_request = $orders[$count]");
        treat_request_concept($orders[$count]);
    }
    for ($count = 0; $count < sizeof($remove); $count++) {
        $delete->execute("delete from pedidocon where id_request = $remove[$count]");
    }
    ?>

    <script language="javascript1.3">

        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace('main.php?id_projeto=' + '<?= $_SESSION['current_id_project'] ?>');

    </script>

    <h4>Opera��o efetuada com sucesso!</h4>
    <script language="javascript1.3">

        self.close();

    </script>

    <?php } else {
    ?>
    <html>
        <head>
            <title>Pedidos de altera��o dos Conceitos</title>
        </head>
        <body>
            <h2>Pedidos de Altera��o no Conjunto de Conceitos</h2>
            <form action="?id_project=<?= $id_project ?>" method="post">

    <?php
    
// Scenario - Verify the modification orders of concepts
//Objective:  Allows the administrator to manage the modification orders of concepts.
//Context:    Manager wants to see the modification orders of concepts.
//Precondition: Login, registered project.
//Actors:	Administrator
//Resources:	System, database.
//Episodes:     The administrator clicks on the option 'Verificar pedidos de alteração de conceitos'.
//Restrictions: Only the projects administrator can have this function visible.
//           The system provides to the administrator a screen wherer he can see the history
//           of all the pending modifications or not for the concepts.
//           For all the new inclusion and modification orders of concepts,
//           the system allows the administrator to aprove or remove.
//           For all the inclusion and modification orders already aproved,
//           the system only enables the option 'Remover' to the administrators.
//           To finish the selection os aprovals e removals, the administrator must click on 'Processar'.
   
    
    $DB = new PGDB ();
    $select = new QUERY($DB);
    $select2 = new QUERY($DB);
    $select->execute("SELECT * FROM pedidocon WHERE id_project = $id_project");
    if ($select->getntuples() == 0) {
        
        echo "<BR>Nenhum pedido.<BR>";
    
        
    } else {
        
        $i = 0;
        $record = $select->gofirst();
        
        while ($record != 'LAST_RECORD_REACHED') {
            
            $id_user = $record['id_user'];
            $id_request = $record['id_request'];
            $order_type = $record['order_type'];
            $aproved = $record['aproved'];
            
            assert($id_user != NULL);
            assert($id_request != NULL);
            assert($order_type != NULL);
            assert($aproved != NULL);
            
            assert(is_int($id_user));
            assert(is_int($id_request));
            
            
            $select2->execute("SELECT * FROM user WHERE id_user = $id_user");
            $user = $select2->gofirst();
            
            if (strcasecmp($order_type, 'remover')) {
                
                ?>

                            <br>
                            <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o conceito <font color="#ff0000"><?= $record['nome'] ?></font> <? if (!strcasecmp($order_type, 'alterar')) {
                    echo"para conceito abaixo:</h3>";
                } else {
                    echo"</h3>";
                } ?>
                                <table>
                                    <td><b>Nome:</b></td>
                                    <td><?= $record['nome'] ?></td>
                                    <tr>
                                        <td><b>Descri��o:</b></td>
                                        <td><?= $record['descricao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Namespace:</b></td>
                                        <td><?= $record['namespaca'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Justificativa:</b></td>
                                        <td><textarea name="justificativa" cols="48" rows="2"><?= $record['justificativa'] ?></textarea></td>
                                    </tr>
                                </table>
                            <?php } else { ?>
                                <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o conceito <font color="#ff0000"><?= $record['nome'] ?></font></h3>
                            <?php
                            }
                            if ($aproved == 1) {
                                echo "<font color=\"#ff0000\">Aprovado</font> ";
                            } else {
                                echo "Aprovar<input type=\"checkbox\" name=\"pedidos[]\" value=\"$id_request\">";
                                echo "Rejeitar<input type=\"checkbox\" name=\"remover[]\" value=\"$id_request\">";
                            }
                            echo "<br>\n<hr color=\"#000000\"><br>\n";
                            $record = $select->gonext();
                        }
                    }
                    ?>
                    <input name="submit" type="submit" value="Processar">
                    </form>
                    <br><i><a href="showSource.php?file=ver_pedido_cenario.php">Veja o c�digo fonte!</a></i>
                    </body>
                    </html>
                    <?php
                }
                ?>

