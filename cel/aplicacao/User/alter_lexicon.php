<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

// alt_lexico.php: This script makes a request for alteration of a project lexicon.
//                 The User receives a form with the current lexicon (with completed fields)
//                 and may make changes in all fields but name. At the end of the main screen
//                 returns to the start screen and the tree and closed. The form of alteration and tb closed.
// Called by file: main.php

session_start();

require_once'../Functions/lexicon_functions.php';
require_once '/Functions/check_User.php';
include("httprequest.inc");
include_once("bd.inc");

check_User("index.php");


$connect = bd_connect() or die("Erro ao conectar ao SGBD");

$synonymList = null;
if (isset($_POST['submit'])) {

    if (!isset($synonymList))
        $synonymList = array();

    //removes synonymous if there is a void.
    $count = count($synonymList);
    for ($i = 0; $i < $count; $i++) {
        if ($synonymList[$i] == "") {
            $synonymList = null;
        }
    }
    //$count = count($listSinonimo);

    foreach ($synonymList as $key => $synonymList) {
        $synonymList[$key] = str_replace(">", " ", str_replace("<", " ", $synonym));
    }


    insertRequestAlterLexicon(
            $_POST['id_project'], 
            $_POST['id_lexicon'], 
            $_POST['name'], 
            $_POST['notion'], 
            $_POST['impact'], 
            $_POST['justification'], 
            $_SESSION['current_id_user'], 
            $synonymList, 
            $_POST['classification']);
    ?>

    <html>
        <head>
            <title>Alter Lexicon</title>
        </head>
        <body>
            <script language="javascript1.3">

                opener.parent.frames['code'].location.reload();
                opener.parent.frames['text'].location.replace('main.php?id_project=<?= $_SESSION['current_id_project'] ?>');

            </script>

            <h4>Operacao Efetuada com Sucesso!!</h4>

            <script language="javascript1.3">
         
                self.close();

            </script>

            <?php
        } else {
            $project_name = simple_query("name", "project", "id_project = " . $_SESSION['current_id_project']);
            $query = "SELECT * FROM lexicon WHERE id_lexicon = id_lexicon";
            $query_result_sql = mysql_query($query) or die(" Erro ao executar a consulta");
           $result = mysql_fetch_array($query_result_sql);

            //synonym
            // $DB = new PGDB () ;
            // $selectSin = new QUERY ($DB) ;
            // $selectSin->execute("SELECT name FROM synonym WHERE id_lexicon = id_lexicon");
            $query_sin = "SELECT name FROM synonym WHERE id_lexicon = id_lexicon";
            $query_result_sql_sin = mysql_query($query_sin) or die(" Erro ao executar a consulta");
            //$resultSin = mysql_fetch_array($query_resulat_Sin);
            ?>
        <html>
            <head>
                <title>Alterar Lexico</title>
            </head>
            <body>
                <script language="JavaScript">
                    <!--
                    function TestEmpty(form)
                    {
                        notion = form.notion.value;
        	
                        if( notion == "" )
                        { alert (" Por favor, forneca o NOME do lexicon.\n O campo NOME é obrigatorio.");
                            form.notion.focus();
                            return false;
                        }

                    }
                    function addSynonym()
                    {
                        synonymList = document.forms[0].elements['synonymList[]']; 
        	
                        if(document.forms[0].synonym.value == "")
                            return;
        	
                        synonymList.options[synonymList.length] = new Option(document.forms[0].synonym.value, document.forms[0].synonym.value);
        	
                        document.forms[0].synonym.value = "";
        	
                        document.forms[0].synonym.focus();

                    }

                    function deleteSynonym()
                    {
                        synonymList = document.forms[0].elements['synonymList[]']; 
        	
                        if(synonymList.selectedIndex == -1)
                            return;
                        else
                            synonymList.options[synonymList.selectedIndex] = null;
        	
                        deleteSynonym();
                    }

                    function doSubmit()
                    {
                        synonymList = document.forms[0].elements['synonymList[]']; 
        	
                        for(var i = 0; i < synonymList.length; i++) 
                            synonymList.options[i].selected = true;
        	
                        return true;
                    }

                    //-->




    <?php
//Scenario -  Alter L�xicon
//Objective:  Allow the user to change an entry in the lexicon	
//Context:	  User want to change a lexicon previously registered
//            Pre-Condition: Login, lexicon word registered
//Actor:	  User
//Resources:  System, registered data
//Episodes:	  The System offers the User the same screen previously displayed include
//            lexical. The screen contains the following data from the lexical to be
//            changed: NOTION and IMPACT. The field PROJECT and NAME are already filled,
//            but they can't be edited. Displays a field JUSTIFICATION for User write
//            the justification fot the change made.	
    ?>

                </SCRIPT>

                <h4>Alterar Simbolo</h4>
                <br>
                <form action="?id_project=<?= $id_project ?>" method="post" onSubmit="return(doSubmit());">
                    <table>
                        <input type="hidden" name="id_lexicon" value="<?= $result['id_lexicon'] ?>">

                        <tr>
                            <td>Projeto:</td>
                            <td><input disabled size="48" type="text" value="<?= $project_name ?>"></td>
                        </tr>
                        <tr>
                            <td>Nome:</td>
                            <td><input disabled maxlength="64" name="nome_visivel" size="48" type="text" value="<?= $result['name']; ?>">
                                <input type="hidden"  maxlength="64" name="name" size="48" type="text" value="<?= $result['name']; ?>">
                            </td>
                        </tr>

                        <tr valign="top">
                            <td>Sinonimo:</td>
                            <td width="0%">
                                <input name="synonym" size="15" type="text" maxlength="50">             
                                &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Add" onclick="addSynonym();">
                                &nbsp;&nbsp;<input type="button" value="Remove" onclick="deleteSynonym();">&nbsp;
                            </td>
                        </tr>

                        <tr> 
                            <td>
                            </td>   
                            <td width="100%">
                        <left><select multiple name="synonymList[]"  style="width: 400px;"  size="5"><?php
    while ($rowSin = mysql_fetch_array($query_result_sql_sin)) {
        ?>
                                    <option value="<?= $rowSin["name"] ?>"><?= $rowSin["name"] ?></option>
                                    <?php
                                }
                                ?>
                                <select></left><br> 
                                    </td>
                                    </tr>

                                    <tr>
                                        <td>Nocao:</td>
                                        <td>
                                            <textarea name="notion" cols="48" rows="3" ><?= $result['notion']; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Impacto:</td>
                                        <td>
                                            <textarea name="impact" cols="48" rows="3"><?= $result['impact']; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Classificacao:</td>
                                        <td>
                                            <SELECT id='classification' name='classification' size=1 width="300">
                                                <OPTION value='subject' <?php if ($result['type'] == 'subject') echo "selected" ?>>Sujeito</OPTION>
                                                <OPTION value='object' <?php if ($result['type'] == 'object') echo "selected" ?>>Objeto</OPTION>
                                                <OPTION value='verb' <?php if ($result['type'] == 'verb') echo "selected" ?>>Verbo</OPTION>
                                                <OPTION value='estate' <?php if ($result['type'] == 'estate') echo "selected" ?>>Estado</OPTION>
                                            </SELECT>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Justificacao para a alteracao:</td>
                                        <td><textarea name="justification" cols="48" rows="6"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td align="center" colspan="2" height="60">
                                            <input name="submit" type="submit" onClick="return TestEmpty(this.form);" value="Alter Symbol">
                                        </td>
                                    </tr>
                                    </table>
                                    </form>
                                    <center><a href="javascript:self.close();">Fechar</a></center>
                                    <br><i><a href="showSource.php?file=alt_lexico.php">Olhe o codigo fonte!</a></i>
                                    </body>
                                    </html>

                                    <?php
                                }
                                ?>
