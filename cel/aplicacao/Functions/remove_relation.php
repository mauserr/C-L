<?php

###################################################################
# File: remove_relation.php
# Purpose: Recieve a the a project id, and removes all
# links and relatioships existents.
###################################################################
if (!(function_exists("remove_relation"))) {
    function remove_relation($id_project, $id_relation){
        assert(is_int($id_project));
        assert(is_int($id_relation));
        assert($id_project !=NULL);
        assert($id_relation !=NULL);
        
        
        $DB = new PGDB () ;

        $sql6 = new QUERY ($DB) ;
        
        # Remove o conceito escolhido
        $sql6->execute ("DELETE FROM relation WHERE id_relation = $id_relation") ;
        $sql6->execute ("DELETE FROM relation_concept WHERE id_relation = $id_relation") ;
        
    }
    
}
?>
