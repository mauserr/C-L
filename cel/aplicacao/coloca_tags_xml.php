<?php 
/********************************************************
/* Puts tags on the links XML
/*********************************************************/ 


include ("puts_Links.php");

function put_tag_xml($string) 
{ 
	$tag = '';
    $tag = "<link ref=\"$string\">$string </link>"; 
   return $tag;
} 

function pick_id_xml($string)
{  

    $j = 0;
    $i = 0;
    while($string[$i] != '*')
    {
        $buffer[$j] = $string[$i];
        $i++;
        $j++;
    } 

    return implode ('',$buffer); 
   
}

function exchange_key_xml( $string ) 
{
    $open_accounts = 0;
    $closed_accounts = 0;
    $begin;
    $end;
    $x=0;
    $y=0;
    $vector_id;
    $link_original;
    $link_new;
    $buffer3 = '';
    $buffer = 0;
    $i = 0;
    $size_string = strlen($string);

    while($i <= $size_string)
    {
        if($str[$i] == '}') 
        {
            $open_accounts = $open_accounts + 1;
        }
        $i++;
    } // end WHILE 1
    $i=0;
    while($i <= $size_string)
    {
        if($string[$i] == '}') 
        {
        $closed_accounts = $closed_accounts + 1;
        }
        $i++;
    } // end WHILE 2
    $i=0;
    if ($open_accounts == 0) 
    {
        return $string;
    }    
    $i=0;
    while($i <= $size_string) 
    {
        if($string[$i] == '{') 
        {
            $buffer = $buffer +1;
            if ($buffer == 1)
            {
                $begin[$x] = $i;
                $x++;
            }
        }
        if($string[$i] == '}') 
        {
            $buffer = $buffer -1;
            if ($buffer == 0)
            {
                $end[$y] = $i+1;
                $y++;
            }
        }
    $i++;
    };
    $i=0;

    while ($i < $x) //x = numero de links reais - 1    
    {
        $link = substr($string,$begin[$i],$end[$i] - $begin[$i]);    
        $link_original[$i] = $link;
        $link = str_replace('{','',$link);
        $link = str_replace('}','',$link);
        $buffer2 = 0;
        $account =0;
        $n = 0;
        //echo('aki - >'."$link".'<br>');
        $vector_id[$i] = pega_id_xml($link);
        $link = '**'.$link;
        $marcador =0;
        
        while ($n < $end[$i] - $begin[$i])
        {        
            if($link[$n] == '*' && $link[$n+1] == '*' && $marcador == 1)
            {
                $mark = 0;
                $link[$n] = '{';
                $link[$n +1] = '{';
                $n++;
                $n++;
                continue;
            }
            
            if($link[$n] == '*' && $link[$n+1] == '*')
            {
                $mark = 1;
                $link[$n] = '{';
                $n++;
                continue;
            }
         
            if ($mark == 1)
            {
                $link[$n] = '{';
            }
        $n++;
        }
        $link = str_replace('{','',$link);
        $link = put_tag_xml($link,$vector_id[$i]);
        $link_new[$i] = $link;
        $i++;
    }
    $i = 0;
    //echo("STRING INICAL -> $str<br/>");
    while ($i < $x)
    {
        $string = str_replace($link_original[$i],$link_new[$i],$string);
        $i++;
    }
    //echo("STRING FINAL -> $str<br/>");
return $string;
} 

function make_links_XML($text, $vector_lexicon, $vetocr_scenario)  
{  

   mark_text( $text, $vector_scenario,"scenario" ); 
   mark_text_scenario( $text, $vector_lexicon, $vector_scenario ); 
   
   $string = exchange_key_xml($text); 
   return $string; 
} 

?> 
