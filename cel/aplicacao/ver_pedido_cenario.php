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
include("httprequest.inc");

 // Checks if the user was authenticated
chkUser("index.php");

if (isset($submit)) {
    
    $DB = new PGDB ();
    $select = new QUERY($DB);
    $update = new QUERY($DB);
    $delete = new QUERY($DB);
    
    for ($count = 0; $count < sizeof($pedidos); $count++) {
        
        $update->execute("update pedidocen set aprovado= 1 where id_pedido = $pedidos[$count]");
        tratarPedidoCenario($pedidos[$count]);
        
    }
    
    
    for ($count = 0; $count < sizeof($remover); $count++) {
        
        $delete->execute("delete from pedidocen where id_pedido = $remover[$count]");
        
    }
    ?>

    <script language="javascript1.3">

        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace('main.php?id_projeto=' + '<?= $_SESSION['id_projeto_corrente'] ?>');

    </script>

    <h4>Opera��o efetuada com sucesso!</h4>
    <script language="javascript1.3">

        self.close();

    </script>

    <?php } else {
    ?>
    <html>
        <head>
            <title>Pedidos de altera��o dos Cen�rios</title>
        </head>
        <body>
            <h2>Pedidos de Altera��o no Conjunto de Cen�rios</h2>
            <form action="?id_projeto=<?= $id_projeto ?>" method="post">

    <?php
    
    
// Scenario - Verify the modification orders of scenarios
//Objective:  Allows the administrator to manage the modification orders of scenarios.
//Context:    Manager wants to see the modification orders of scenarios.
//Precondition: Login, registered project.
//Actors:	Administrator
//Resources:	System, database.
//Episodes:     The administrator clicks on the option 'Verificar pedidos de alteração de cenários'.
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
    $select->execute("SELECT * FROM pedidocen WHERE id_projeto = $id_projeto");
    
    
    if ($select->getntuples() == 0) {
        
        echo "<BR>Nenhum pedido.<BR>";
        
    } else {
        
        $i = 0;
        $record = $select->gofirst();
        
        while ($record != 'LAST_RECORD_REACHED') {
            
            $id_user = $record['id_usuario'];
            $id_order = $record['id_pedido'];
            $order_type = $record['tipo_pedido'];
            $aproved = $record['aprovado'];
            $select2->execute("SELECT * FROM usuario WHERE id_usuario = $id_user");
            $user = $select2->gofirst();
            
            if (strcasecmp($order_type, 'remover')) {
                
                ?>

                            <br>
                            <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o cen�rio <font color="#ff0000"><?= $record['titulo'] ?></font> <? if (!strcasecmp($order_type, 'alterar')) {
                    echo"para cen�rio abaixo:</h3>";
                    
                } else {
                    
                    echo"</h3>";
                    
                } ?>
                                <table>
                                    <td><b>T�tulo:</b></td>
                                    <td><?= $record['titulo'] ?></td>
                                    <tr>
                                        <td><b>Objetivo:</b></td>
                                        <td><?= $record['objetivo'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Contexto:</b></td>
                                        <td><?= $record['contexto'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Atores:</b></td>
                                        <td><?= $record['atores'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Recursos:</b></td>
                                        <td><?= $record['recursos'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Exce��o:</b></td>
                                        <td><?= $record['excecao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Epis�dios:</b></td>
                                        <td><textarea cols="48" name="episodios" rows="5"><?= $record['episodios'] ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><b>Justificativa:</b></td>
                                        <td><textarea name="justificativa" cols="48" rows="2"><?= $record['justificativa'] ?></textarea></td>
                                    </tr>
                                </table>
                            <?php } else { ?>
                                <h3>O usu�rio <a  href="mailto:<?= $user['email'] ?>" ><?= $user['nome'] ?></a> pede para <?= $order_type ?> o cen�rio <font color="#ff0000"><?= $record['titulo'] ?></font></h3>
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
                    <br><i><a href="showSource.php?file=ver_pedido_cenario.php">Veja o c�digo fonte!</a></i>
                    </body>
                    </html>
                    <?php
                }
                ?>

