<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// ver_pedido_cenario.php: This script shows all the orders refered to the scenario.
// The manager has the option to see the orders already validated.
// The manager can also validate and process the orders.
// The manager will have a third option, to remove the order
// validated or not from the orders list. The manager can answer
// to an order via email directly from this page.



session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
require_once '/Functions/scenario_Functions.php';
include("httprequest.inc");


check_User("index.php");

$submit = null;
$request = null;
$remove = null;
$id_project = null;
$order_type = null;
if (isset($submit)) {
    
    $DB = new PGDB ();
    $select = new QUERY($DB);
    $update = new QUERY($DB);
    $delete = new QUERY($DB);
    
    for ($count = 0; $count < sizeof($request); $count++) {
        
        $update->execute("update request_scenario set aproved= 1 where id_request = $request[$count]");
        tratarPedidoCenario($request[$count]);
        
    }
    
    
    for ($count = 0; $count < sizeof($remove); $count++) {
        
        $delete->execute("delete from request_scenario where id_request = $remove[$count]");
        
    }
    ?>

    <script language="javascript1.3">

        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace('main.php?id_project=' + '<?= $_SESSION['current_id_project'] ?>');

    </script>

    <h4>Operação efetuada com sucesso!</h4>
    <script language="javascript1.3">

        self.close();

    </script>

    <?php } else {
    ?>
    <html>
        <head>
            <title>Pedidos de alteração dos Cenários</title>
        </head>
        <body>
            <h2>Pedidos de Alteração no Conjunto de Cenários</h2>
            <form action="?id_project=<?= $id_project ?>" method="post">

    <?php
    
    
// Scenario - Verify the modification orders of scenarios
//Objective:  Allows the administrator to manage the modification orders of scenarios.
//Context:    Manager wants to see the modification orders of scenarios.
//Precondition: Login, registered project.
//Actors:	Administrator
//Resources:	System, database.
//Episodes:     The administrator clicks on the option 'Verificar pedidos de alteraÃ§Ã£o de cenÃ¡rios'.
//Restrictions: Only the projects administrator can have this function visible.
//           The system provides to the administrator a screen wherer he can see the history
//           of all the pending modifications or not for the scenarios.
//           For all the new inclusion and modification orders of scenarios,
//           the system allows the administrator to aprove or remove.
//           For all the inclusion and modification orders already aproved,
//           the system only enables the option 'Remover' to the administrators.
//           To finish the selection os aprovals e removals, the administrator must click on 'Processar'.

    
    
    $DB = new PGDB ();
    $select = new QUERY($DB);
    $select2 = new QUERY($DB);
    $select->execute("SELECT * FROM request_scenario WHERE id_project = '$id_project'");
    
    
    if ($select->getntuples() == 0) {
        
        echo "<BR>Nenhum pedido.<BR>";
        
    } else {
        
        $i = 0;
        $record = $select->gofirst();
        
        while ($record != 'LAST_RECORD_REACHED') {
            
            $id_user = $record['id_user'];
            $id_order = $record['id_request'];
            $type_request = $record['type_request'];
            $aproved = $record['aproved'];
            $select2->execute("SELECT * FROM user WHERE id_user = $id_user");
            $user = $select2->gofirst();
            
            if (strcasecmp($type_request, 'remover')) {
                
                ?>

                            <br>
                            <h3>O usuário <a  href="mailto:<?= $user['email'] ?>" ><?= $user['name'] ?></a> pede para <?= $order_type ?> o cenário <font color="#ff0000"><?= $record['title'] ?></font> <? if (!strcasecmp($order_type, 'alter')) {
                    echo"para cenário abaixo:</h3>";
                    
                } else {
                    
                    echo"</h3>";
                    
                } ?>
                                <table>
                                    <td><b>Título:</b></td>
                                    <td><?= $record['titulo'] ?></td>
                                    <tr>
                                        <td><b>Objetivo:</b></td>
                                        <td><?= $record['objective'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Contexto:</b></td>
                                        <td><?= $record['context'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Atores:</b></td>
                                        <td><?= $record['actors'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Recursos:</b></td>
                                        <td><?= $record['resource'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Exceçãoo:</b></td>
                                        <td><?= $record['exception'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Episódios:</b></td>
                                        <td><textarea cols="48" name="episodes" rows="5"><?= $record['episodes'] ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><b>Justificativa:</b></td>
                                        <td><textarea name="justificative" cols="48" rows="2"><?= $record['justificative'] ?></textarea></td>
                                    </tr>
                                </table>
                            <?php } else { ?>
                                <h3>O usuário <a  href="mailto:<?= $user['email'] ?>" ><?= $user['name'] ?></a> pede para <?= $order_type ?> o cenário <font color="#ff0000"><?= $record['title'] ?></font></h3>
                            <?php
                            }
                            if ($aproved == 1) {
                                echo "[<font color=\"#ff0000\"><STRONG>Aprovado</STRONG></font>]<BR>";
                            } else {
                                echo "[<input type=\"checkbox\" name=\"pedidos[]\" value=\"$id_order\"> <STRONG>Aprovar</STRONG>]<BR>  ";
//                     echo "Rejeitar<input type=\"checkbox\" name=\"remover[]\" value=\"$id_order\">" ;
                            }
                            echo "[<input type=\"checkbox\" name=\"remover[]\" value=\"$id_order\"> <STRONG>Remover da lista</STRONG>]";
                            print( "<br>\n<hr color=\"#000000\"><br>\n");
                            $record = $select->gonext();
                        }
                    }
                    ?>
                    <input name="submit" type="submit" value="Processar">
                    </form>
                    <br><i><a href="showSource.php?file=ver_pedido_cenario.php">Veja o código fonte!</a></i>
                    </body>
                    </html>
                    <?php
                }
                ?>

