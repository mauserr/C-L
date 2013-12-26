<?php

include_once("bd.inc"); 

$link = bd_connect();

$query_sql = "show tables" ;
$query_result_sql = mysql_query($query) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 


    print "<font color=#7c75b2 face=arial><h3>TABELAS e seus ATRIBUTOS<h3></font>";

while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
{ 
    print "<table border=1><tr><td bgcolor=#7c75b2 width=120><font color=white>". $line[0] . "</font></td>";
    $table = "describe " . $line[0] ;
    $atributes = mysql_query($table) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
    while ($line = mysql_fetch_array($atributes, MYSQL_BOTH)) 
    {  
       print  "<td>" . $linha[0] . " </td>";
    }
    print "</tr></table><br>";
} 


  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>". $line[4] . "</td><td>". $line[5] . "</td><td>";
  }
  print "</table>";

  /* PEDIDOREL */

$results = "select * from request_relation order by name" ;
$result = mysql_query($results) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>PedidoRel<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_pedido</font></td> 
                  <td bgcolor=#7c75b2 width=120><font color=white>id_usuario</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>tipo_pedido</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>aprovado</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>id_relacao</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>justificativa</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>id_status</font></td>
  
           </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>" . $line[4] . "</td><td>" . $line[5] . "</td><td>" . $line[6] . "</td><td>" . $line[7] . "</td><td>" . $line[8] . "</td><td>";
  }
  print "</table>";

/* LEXICO */

$results = "select * from lexicon order by name" ;
$result = mysql_query($results) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Lexico<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_lexico</font></td> 
                  <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>data</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>tipo</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>nocao</font></td>
  				  <td bgcolor=#7c75b2 width=120><font color=white>impacto</font></td>
  
           </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>" . $line[4] . "</td><td>" . $line[5] . "</td><td>" . $line[6] . "</td><td>" . $line[7] . "</td><td>";
  }
  print "</table>";
    
  
/* ALGORITMO */

$results = "select * from algorithm" ;
$result = mysql_query($results) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Algoritmo<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_variavel</font></td> 
                  <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>valor</font></td>                  
           </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>";
  }
  print "</table>";

/* CONCEITOS */

$concept = "select * from conceito order by nome asc" ;
$result = mysql_query($concept) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Conceitos<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_conceito</font></td> 
                  <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>descricao</font></td>
                  <td bgcolor=#7c75b2 width=120><font color=white>pai</font></td>
           </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>". $line[4] . "</td><td>". $line[5] . "</td><td>";
  }
  print "</table>";




/* RELAÇÕES */

$relations = "select * from relation order by id_relation" ;
$result = mysql_query($relations) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Relações<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_relacao</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>";
  }
  print "</table>";


/* HIERARQUIA */

$hierarchy = "select * from hierarchy" ;
$result = mysql_query($hierarchy) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Hierarquia<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_hierarquia</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>id_conceito</font></td>
			 <td bgcolor=#7c75b2 width=120><font color=white>id_subconceito</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>";
  }
  print "</table>";



/* RELAÇÕES ENTRE CONCEITOS */

$result_concept = "select c.nome, r.nome, rc.predicado, rc.id_projeto from relacao_conceito rc, relacao r, conceito c WHERE c.id_conceito = rc.id_conceito AND rc.id_relacao = r.id_relacao ORDER BY c.nome, r.nome ASC;" ;
$result = mysql_query($result_concept) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Relação entre conceitos<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>conceito</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>relacao</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>predicado</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" . $line[3] . "</td><td>";
  }
  print "</table>";




/* AXIOMAS */

$axioms = "select * from axiom order by id_axiom" ;
$result = mysql_query($axioms) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Axiomas<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_axioma</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>axioma</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>";
  }
  print "</table>";


/* USUÁRIOS */

$users = "select * from user order by id_user" ;
$result = mysql_query($users) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Usuários<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_usuario</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>nome</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>email</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>login</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>senha</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" .  $line[3] . "</td><td>" . $line[4] . "</td><td>";
  }
  print "</table>";


/* PARTICIPA */

$participates = "select * from participates order by id_project" ;
$result = mysql_query($participates) or die("A consulta à BD falhou : " . mysql_error() . __LINE__); 
  print "<br><br><font color=#7c75b2 face=arial><h3>Participa<h3></font>";
  print "<table border=1>";
  print "<tr><td bgcolor=#7c75b2 width=120><font color=white>id_usuario</font></td> 
             <td bgcolor=#7c75b2 width=120><font color=white>id_projeto</font></td>
             <td bgcolor=#7c75b2 width=120><font color=white>gerente</font></td>
         </tr>";

  while ($line = mysql_fetch_array($result, MYSQL_BOTH)) 
  {  
     print "<tr><td>". $line[0] . "</td><td>". $line[1] . "</td><td>" . $line[2] . "</td><td>" ;
  }
  print "</table>";



mysql_close($link);

?>