<?php 
//**************************************
//* Class: index.php 
//* Classe de �ndice 
//* Licensa:
//**************************************

session_start(); 
include ("funcoes_genericas.php"); 
require_once '/Functions/check_User.php';
Check_User("index.php");        // Cenario: controle de acesso 

?> 

<title>C&L - Cen�rios e L�xico</title> 
<frameset rows="103,*" cols="*" frameborder="NO" border="0" framespacing="0"> 
    <frame src="heading.php" name="heading" scrolling="NO"> 
    <frameset cols="160,40,*" frameborder="NO" border="0" framespacing="0" rows="*"> 
        <frameset rows="0,*" frameborder="NO" border="0" framespacing="0" rows="*"> 
            <frame src="../User/code.php" name="code"> 
            <frame src="menu_Empty.htm" name="menu"> 
        </frameset> 
        <frame src="VertBar.htm" name="VertBar"> 
        <frame src="main.php" name="text"> 
    </frameset> 
</frameset> 
