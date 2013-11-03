<?php
###################################################################
# file: recarrega.php
# purpose: Reloads the current page
#
###################################################################


if (!(function_exists("recarrega"))) 
{
    function recarrega($url) 
    {
		?>
		assert($url != NULL );
		<script language="javascript1.3">
		
		location.replace('<?=$url?>');
		
		</script>
		
		<?php
    }
}
?>
