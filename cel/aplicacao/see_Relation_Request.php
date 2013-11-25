<?php

/*************************************************************
 * File: see_Relation_Request.php
 * purpose: show lots of requires from user, related to the 
 * list of requires. It is called by heading.php
 * 
 ************************************************************/
session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
require_once '/Functions/check_User.php';
check_User("index.php"); 

$submit = null;
$orders = null;
$remove = null;
$id_project = null;

if (isset($submit)) {
        $DB = new PGDB () ;
        $select = new QUERY ($DB) ;
        $update = new QUERY ($DB) ;
        $delete = new QUERY ($DB) ;
        for($count = 0; $count < sizeof($orders); $count++)
        {
                 $update->execute("update pedidorel set aprovado= 1 where id_pedido = $orders[$count]") ;
                 tratarPedidoRelacao($orders[$count]) ;
        }
      for($count = 0; $count < sizeof($remove); $count++)
         {
                  $delete->execute("delete from pedidorel where id_pedido = $remove[$count]") ;
         }
?>

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=' + '<?=$_SESSION['current_id_project']?>');

</script>

<h4>Opera��o efetuada com sucesso!</h4>
<script language="javascript1.3">

self.close();

</script>

<?php
} else {?>
<html>
  <head>
     <title>Pedidos de altera��o das Rela�oes</title>
  </head>
<body>
<h2>Pedidos de Altera��o no Conjunto de Rela��es</h2>
<form action="?id_project=<?=$id_project?>" method="post">

<?php
                $DB = new PGDB () ;
                $select = new QUERY ($DB) ;
                $select2 = new QUERY ($DB) ;
                $select->execute("SELECT * FROM pedidorel WHERE id_projeto = $id_project") ;
                if ($select->getntuples() == 0){
                      echo "<BR>Nenhum pedido.<BR>" ;
                }else{
                    $i = 0 ;
                    $record = $select->gofirst () ;
                    
                    while($record != 'LAST_RECORD_REACHED'){
                        
                            $id_user = $record['id_user'] ;
                            $id_request = $record['id_request'] ;
                            $order_type = $record['order_type'] ;
                            $aproved = $record['aproved'] ;
                            
                            assert($id_user != NULL);
                            assert($id_request != NULL);
                            assert($order_type != NULL);
                            assert($aproved != NULL); 
                           
                            assert(is_int($id_user));
                            assert(is_int($id_request));
                            
                            $select2->execute("SELECT * FROM usuario WHERE id_user = $id_user") ;
                            $user = $select2->gofirst () ;
                            if(strcasecmp($order_type,'remover')){?>
        
        <br>
                <h3>O usu�rio <a  href="mailto:<?=$user['email']?>" ><?=$user['nome']?></a> pede para <?=$order_type?> a rela��o <font color="#ff0000"><?=$record['nome']?></font> <?  if(!strcasecmp($order_type,'alterar')){echo"para conceito abaixo:</h3>" ;}else{echo"</h3>" ;}?>
                    <table>
                <td><b>Nome:</b></td>
                <td><?=$record['nome']?></td>
            <tr>
                <td><b>Justificativa:</b></td>
                <td><textarea name="justificativa" cols="48" rows="2"><?=$record['justificativa']?></textarea></td>
            </tr>
        </table>
<?php    }else{?>
            <h3>O usu�rio <a  href="mailto:<?=$user['email']?>" ><?=$user['nome']?></a> pede para <?=$order_type?> a rela��o <font color="#ff0000"><?=$record['nome']?></font></h3>
<?php }
				if ($aproved == 1)
                {
    			   echo "[<font color=\"#ff0000\"><STRONG>Aprovado</STRONG></font>]<BR>";
	    		} else
                {
				   echo "[<input type=\"checkbox\" name=\"pedidos[]\" value=\"$id_request\"> <STRONG>Aprovar</STRONG>]<BR>  " ;
//                 echo "Rejeitar<input type=\"checkbox\" name=\"remover[]\" value=\"$id_pedido\">" ;
                }
                   echo "[<input type=\"checkbox\" name=\"remover[]\" value=\"$id_request\"> <STRONG>Remover da lista</STRONG>]" ;
                   print( "<br>\n<hr color=\"#000000\"><br>\n") ;
		    	   $record = $select->gonext () ;
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

