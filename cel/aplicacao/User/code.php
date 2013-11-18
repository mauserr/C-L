<?php
session_start();


if (isset($_GET['id_project'])) {
    $id_project = $_GET['id_project'];
}



require_once '/Functions/check_User.php';
require_once'../Functions/project_Functions.php';
include_once("bd.inc");

check_User("index.php");        // Checks if the user was authenticated 
//$id_project = 2; 
?>  

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 

<?php
// conecta ao SGBD 
$connect_db = bd_connect() or die("Erro ao conectar ao SGBD");

// If the variable $id_project is set, it corresponds to the project id that will be displayed.
// If its seted then, by default, no project will be displayed. 
// As the transition is done using JavaScript, we must check 
// if this id realy corresponds to a project that the user have access. 

if (isset($id_project)){
    
     check_project_permanent($_SESSION['current_id_user'], $id_project) or die("Permissao negada");
    
    
    $query_project = "SELECT name FROM project WHERE id_project = $id_project";
    $query_check_error_project = mysql_query($query_project) or die("Erro ao enviar a query");
    $result = mysql_fetch_array($query_check_error_project);
    $name_project = $result['name'];
    
}else{
    
    
    ?>  

    <script language="javascript1.3">

        top.frames['menu'].document.writeln('<font color="red">Nenhum projeto selecionado</font>');

    </script> 

    <?php
    exit();
}
?>  

<html> 
    <head> 

        <script type="text/javascript">
        // Framebuster script to relocate browser when MSIE bookmarks this 
        // page instead of the parent frameset.  Set variable relocateURL to 
        // the index document of your website (relative URLs are ok): 
            /*var relocateURL = "/"; 
     
             if (parent.frames.length == 0) { 
             if(document.images) { 
             location.replace(relocateURL); 
             } else { 
             location = relocateURL; 
             } 
             }*/
        </script> 

        <script type="text/javascript" src="mtmcode.js">
        </script> 

        <script type="text/javascript">
        // Morten's JavaScript Tree Menu 
        // version 2.3.2, dated 2002-02-24 
        // http://www.treemenu.com/ 

        // Copyright (c) 2001-2002, Morten Wang & contributors 
        // All rights reserved. 

        // This software is released under the BSD License which should accompany 
        // it in the file "COPYING".  If you do not have this file you can access 
        // the license through the WWW at http://www.treemenu.com/license.txt 

        // Nearly all user-configurable options are set to their default values. 
        // Have a look at the section "Setting options" in the installation guide 
        // for description of each option and their possible values. 

            MTMDefaultTarget = "text";
            MTMenuText = "<?= $name_project ?>";

            /****************************************************************************** 
             * User-configurable list of icons.                                            * 
             ******************************************************************************/

            var MTMIconList = null;
            MTMIconList = new IconList();
            MTMIconList.addIcon(new MTMIcon("menu_link_external.gif", "http://", "pre"));
            MTMIconList.addIcon(new MTMIcon("menu_link_pdf.gif", ".pdf", "post"));

            /****************************************************************************** 
             * User-configurable menu.                                                     * 
             ******************************************************************************/

            var menu = null;
            menu = new MTMenu();
            menu.addItem("Cenï¿½rios");
        // + submenu 
            var mc = null;
            mc = new MTMenu();

<?php


$query_scenario = "SELECT id_scenario, title  
                  FROM scenario  
                  WHERE id_project = $id_project  
                  ORDER BY title";


$query_check_error = mysql_query($query_scenario) or die("Erro ao enviar a query de selecao");

// We must remove all the HTML tags of the scenario title. Possibly 
// will be link tags. In case its not removed, will be an error when showing 
// it in the menu. This search and replace removes anything that 
// is in tha form <anything_here>.


$search = "'<[\/\!]*?[^<>]*?>'si";
$replace = "";


while ($row = mysql_fetch_row($query_check_error)) {    // For each projects scenario 
    
    $row[1] = preg_replace($search, $replace, $row[1]);
    ?>

                mc.addItem("<?= $row[1] ?>", "main.php?id=<?= $row[0] ?>&t=c");

            // + submenu 
                var mcs_<?= $row[0] ?> = null;
                mcs_<?= $row[0] ?> = new MTMenu();
                mcs_<?= $row[0] ?>.addItem("Sub-cenï¿½rios", "", null, "Cenï¿½rios que este cenï¿½rio referencia");
            // + submenu 
                var mcsrc_<?= $row[0] ?> = null;
                mcsrc_<?= $row[0] ?> = new MTMenu();

    <?php
    
    
    $query_id_scenario = "SELECT c.id_scenario_to, cen.title FROM centocen c, scenario cen WHERE c.id_scenario_from = " . $row[0];
    $query_id_scenario = $query_id_scenario . " AND c.id_scenario_to = cen.id_scenario";
    $qrr_2 = mysql_query($query_id_scenario) or die("Erro ao enviar a query de selecao");
    
    
    while ($row_2 = mysql_fetch_row($qrr_2)){
        
        $row_2[1] = preg_replace($search, $replace, $row_2[1]);
        
        ?>

                    mcsrc_<?= $row[0] ?>.addItem("<?= $row_2[1] ?>", "main.php?id=<?= $row_2[0] ?>&t=c&cc=<?= $row[0] ?>");

        <?php
    }
    ?>

            // - submenu 
                mcs_<?= $row[0] ?>.makeLastSubmenu(mcsrc_<?= $row[0] ?>);

            // - submenu 
                mc.makeLastSubmenu(mcs_<?= $row[0] ?>);

    <?php
}
?>

        // - submenu 
            menu.makeLastSubmenu(mc);




            menu.addItem("Lï¿½xico");
        // + submenu 
            var ml = null;
            ml = new MTMenu();

<?php

$query_lexicon = "SELECT id_lexicon, name  
                  FROM lexicon  
                  WHERE id_project = $id_project  
                  ORDER BY name";

$query_check_error_lexicon = mysql_query($query_lexicon) or die("Erro ao enviar a query de selecao");

while ($row = mysql_fetch_row($query_check_error_lexicon)){   // for each projects lexicon 
    ?>

                ml.addItem("<?= $row[1] ?>", "main.php?id=<?= $row[0] ?>&t=l");
            // + submenu 
                var mls_<?= $row[0] ?> = null;
                mls_<?= $row[0] ?> = new MTMenu();
            // mls_<?= $row[0] ?>.addItem("Lï¿½xico", "", null, "Termos do lï¿½xico que este termo referencia"); 
            // + submenu 
            // var mlsrl_<?= $row[0] ?> = null; 
            // mlsrl_<?= $row[0] ?> = new MTMenu(); 

    <?php
    
    $query_id_lexicon = "SELECT l.id_lexico_to, lex.name FROM lextolex l, lexicon lex WHERE l.id_lexico_from = " . $row[0];
    $query_id_lexicon = $query_id_lexicon . " AND l.id_lexico_to = lex.id_lexicon";
    $qrr_2 = mysql_query($query_id_lexicon) or die("Erro ao enviar a query de selecao");
    
    while ($row_2 = mysql_fetch_row($qrr_2)){
        
        
        ?>

                // mlsrl_<?= $row[0] ?>.addItem("<?= $row_2[1] ?>", "main.php?id=<?= $row_2[0] ?>&t=l&ll=<?= $row[0] ?>"); 
                    mls_<?= $row[0] ?>.addItem("<?= $row_2[1] ?>", "main.php?id=<?= $row_2[0] ?>&t=l&ll=<?= $row[0] ?>");

        <?php
    }
    
    
    ?>

            // - submenu 
            // mls_<?= $row[0] ?>.makeLastSubmenu(mlsrl_<?= $row[0] ?>); 
            // - submenu 
                ml.makeLastSubmenu(mls_<?= $row[0] ?>);

    <?php
    
}
?>

        // -submenu 
            menu.makeLastSubmenu(ml);










        // ONTOLGIA 
        // + submenu 
            menu.addItem("Ontologia");
            var mo = null;
            mo = new MTMenu();

        // -submenu 
            menu.makeLastSubmenu(mo);


        // CONCEITO 
        // ++ submenu 
            mo.addItem("Conceitos");
            var moc = null;
            moc = new MTMenu();

<?php


$query_concept = "SELECT id_concept, name  
                  FROM concept 
                  WHERE id_project = $id_project  
                  ORDER BY name";

$query_check_erro_concept = mysql_query($query_concept) or die("Erro ao enviar a query de selecao");


while ($row = mysql_fetch_row($query_check_erro_concept)){  // for each projects concept
    
    print "moc.addItem(\"$row[1]\", \"main.php?id=$row[0]&t=oc\");";
    
}


?>

        // --submenu 
            mo.makeLastSubmenu(moc);




        // RELAï¿½ï¿½ES 
        // ++ submenu 
            mo.addItem("Relações");
            var mor = null;
            mor = new MTMenu();

<?php


$query_relation = "SELECT   id_relation, name 
                  FROM     relation r 
                  WHERE    id_project = $id_project  
                  ORDER BY name";

$query_check_erro_relation = mysql_query($query_relation) or die("Erro ao enviar a query de selecao");


while ($row = mysql_fetch_row($query_check_erro_relation)){   // For each projects relation 
    
    print "mor.addItem(\"$row[1]\", \"main.php?id=$row[0]&t=or\");";
    
}
?>

        // --submenu    
            mo.makeLastSubmenu(mor);




        // AXIOMAS 
        // ++ submenu 
            mo.addItem("Axiomas");
            var moa = null;
            moa = new MTMenu();

<?php


$query_axiom = "SELECT   id_axiom, axiom 
                 FROM     axiom 
                 WHERE    id_project = $id_project  
                 ORDER BY axiom";

$query_check_error_axiom = mysql_query($query_axiom) or die("Erro ao enviar a query de selecao");


while ($row = mysql_fetch_row($query_check_error_axiom)){  // For each projects axioms 
    
    $axi = explode(" disjoint ", $row[1]);
    print "moa.addItem(\"$axi[0]\", \"main.php?id=$row[0]&t=oa\");";
    
    
}
?>

        // --submenu    
            mo.makeLastSubmenu(moa);



        </script> 
    </head> 
    <body onload="MTMStartMenu(true)" bgcolor="#000033" text="#ffffcc" link="yellow" vlink="lime" alink="red"> 
    </body> 
</html> 
