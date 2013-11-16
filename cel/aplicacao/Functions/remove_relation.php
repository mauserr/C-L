<?php

###################################################################
# File: remove_relation.php
# Purpose: Recieve a the a project id, and removes all
# links and relatioships existents.
###################################################################
if (!(function_exists("remove_relation"))) {
    function remove_relation($id_project, $id_relation){
        assert(is_int($id_project, $id_relation));
        assertNotNull($id_project, $id_relation);
        
        
        $DB = new PGDB () ;

        $sql6 = new QUERY ($DB) ;
        
        # Remove o conceito escolhido
        $sql6->execute ("DELETE FROM relation WHERE id_relation = $id_relation") ;
        $sql6->execute ("DELETE FROM relation_concept WHERE id_relation = $id_relation") ;
        
    }
    
}
?>
