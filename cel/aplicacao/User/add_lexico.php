<?php
include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");
include_once("bd.inc");

// add_lexico.php: This script registers a new term in the lexicon of the project.
//                 It last through the URL, a variable $id_project, that
//                 indicates that the project should be inserted the new term.

session_start();

if (!isset($sucess)) {
    $sucess = 'n';
}

check_User("index.php");


$connect_db = db_connect() or die("Erro ao conectar ao SGBD");


if (isset($submit)) {

    $ret = checkExistingLexical($_SESSION['current_id_project'], $name);
    if (!isset($synonymList))
        $synonymList = array();

    $retSinonimo = checkSynonym($_SESSION['current_id_project'], $synonymList);

    if (($ret == true) AND ($retSin == true )) {
        $current_id_user = $_SESSION['current_id_user'];
        insertRequestAddLexicon(
                $id_project = '',
                $name = '',
                $notion = '',
                $impact = '',
                $current_id_usuario = '',
                $synonymList = '',
                $classification = ''
                );
    } else {
        ?>
        <html>
            <head>
                <title>Projeto</title>
            </head>
            <body bgcolor="#FFFFFF">
                <p style="color: red; font-weight: bold; text-align: center">Este simbolo ou sinonimo ja existe!</p>
                <br>
                <br>
            <center>
                <a href="JavaScript:window.history.go(-1)">Voltar</a>
            </center>
        </body>
        </html>
        <?php
        return;
    }
    $ipValue = CELConfig_ReadVar("HTTPD_ip");
    ?>

    <script language="javascript1.2">

        opener.parent.frames['code'].location.reload();
        opener.parent.frames['text'].location.replace('main.php?id_project=<?= $_SESSION['current_id_project'] ?>');
        location.href = "add_lexicon.php?id_project=<?= $id_project ?>&sucess=s"; 

    </script>
    <?php
// Script chamado atrav�s do menu superior
} else {
    $query_sql = "SELECT nome FROM projeto WHERE id_projeto = $id_project";
    $query_result_sql = mysql_query($query_sql) or die(" Erro ao executar a consulta");
    $result = mysql_fetch_array($query_result_sql);
    $project_name = $result['name'];
    ?>

    <html>
        <head>
            <title>Adicionar Lexico</title>
        </head>
        <body>
            <script language="JavaScript">
                <!--
                function TestEmpty(form)
                {
                    name  = form.name.value;
                    notion = form.notion.value;

                    if( name == "" )
                    { 
                        alert (" Por favor, forneca o NOME do lexico.\n O campo NOME é obrigatorio.");
                        form.name.focus();
                        return false;
                    }else{
                        pattern = /[\\\/\?"<>:|]/;
                        nOK = pattern.exec(name);
                        if (nOK)
                        {
                            window.alert (" O NOME do lexico não pode conter os seguintes caracteres:  / \\ : ? \" < > |");
                            form.nome.focus();
                            return false;
                        } 
                    }
            
                    if( notion == "" )
                    { alert (" Por favor, forneca o NOME do lexico.\n O campo NOME deve ser preenchido.");
                        form.nocao.focus();
                        return false;
                    }

                }
                function addSynonym()
                {
                    synonymList = document.forms[0].elements['synonymList[]']; 

                    if(document.forms[0].synonym.value == "")
                        return;

                    synonym = document.forms[0].synonym.value;
                    pattern = /[\\\/\?"<>:|]/;
                    nOK = pattern.exec(synonym);
                    if (nOK)
                    {
                        window.alert ("O sinonimo do lexico não pode conter os seguintes caracteres:  / \\ : ? \" < > |");
                        document.forms[0].synonym.focus();
                        return;
                    } 
        	
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
//Scenario -  Insert Lexicon
//Objective:    Allow the user to include a new word of the lexicon
//Context:      User wishes include a new word of the lexicon.
//              Pre-Condition: Login, lexicon word still not registered
//Actors:       User, System
//Resources:    Data to be registered
//Episodes:     The System should provides to the user a window with the following text fields:
//               - Lexicon Entrance.
//               - Notion.   Restriction: Text box with at least 5 written visible lines.
//               - Impact. Restriction: Text box with at least 5 written visible lines.
//              Button to confirm the inclusion of the new lexicon entrance
//              Restrictions: After pressing the confirmation button, the system veryfies all
//              the fields were filled. 
//Exception:    if all the fields were not filled, returns to the user a message.
//              warning that all the fields must be filled and one button must go back to the last page.
    ?>

            </SCRIPT>

            <h4>Adicionar Simbolo</h4>
            <br>
            <?php
            if ($sucess == "s") {
                ?>
                <p style="color: blue; font-weight: bold; text-align: center">Simbolo
                    inserido com sucesso!</p>
                <?php
            }
            ?>
            <form action="?id_project=<?= $id_project ?>" method="post"
                  onSubmit="return(doSubmit());">
                <table>
                    <tr>
                        <td>Projeto:</td>
                        <td><input disabled size="48" type="text" value="<?= $name_project ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Nome:</td>
                        <td><input size="48" name="name" type="text" value=""></td>
                    </tr>
                    <tr valign="top">
                        <td>Synonyms:</td>
                        <td width="0%"><input name="synonym" size="15" type="text"
                                              maxlength="50"> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button"
                                              value="Add" onclick="addSynonym()"> &nbsp;&nbsp;<input
                                              type="button" value="Remove" onclick="deleteSynonym()">&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td width="100%"><left> <select multiple name="synonymList[]"
                                                    style="width: 400px;" size="5"></select></left> <br>
                    </td>


                    <tr>
                    </tr>
                    </tr>
                    <tr>
                        <td>Nocao:</td>
                        <td><textarea cols="51" name="notion" rows="3" WRAP="SOFT"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Impacto:</td>
                        <td><textarea cols="51" name="impact" rows="3" WRAP="SOFT"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Classificacao:</td>
                        <td><SELECT id='classification' name='classification' size=1
                                    width="300">
                                <OPTION value='subject' selected>Sujeito</OPTION>
                                <OPTION value='object'>Objeto</OPTION>
                                <OPTION value='verb'>Verbo</OPTION>
                                <OPTION value='state'>Estado</OPTION>
                            </SELECT>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2" height="60"><input name="submit"
                                                                          type="submit" onClick="return TestEmpty(this.form);"
                            value="Add symbol"><BR> <BR> </script> <!--            <A HREF="RegrasLAL.html" TARGET="new">See rules of the LAL</A><BR>   -->
                            <A HREF="#"
                               OnClick="javascript:open( 'RegrasLAL.html' , '_blank' , 'dependent,height=380,width=520,titlebar' );">
                                Olhe as regras do <i>LAL</i>
                            </A>
                        </td>
                    </tr>
                </table>
            </form>
        <center>
            <a href="javascript:self.close();">Fechar</a>
        </center>
        <br>
        <i><a href="showSource.php?file=add_lexico.php">Olhe o codigo fonte!</a>
        </i>
    </body>

    </html>

    <?php
}
?>
