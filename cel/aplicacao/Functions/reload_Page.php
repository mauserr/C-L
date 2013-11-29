<?php
/***************************************
 * File: reload_Page.php
 * Purpose: Refreshes the current page in order to reload parameters
 * 
 */

if (!(function_exists("reload_Page"))){
    
    function reload_Page($url) {
        
            assert(is_string($url));
            assert($url !=NULL);
		?>
		
		<script language="javascript1.3">
		
		location.replace('<?=$url?>');
		
		</script>
		
		<?php
    }
}

?>
