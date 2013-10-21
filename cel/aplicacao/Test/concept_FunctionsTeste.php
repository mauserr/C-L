<?php
require_once dirname(__FILE__).'/../Functions/concept_Functions.php';
require_once dirname(__FILE__).'/../Functions/project_Functions.php';

class concept_FunctionsTeste extends PHPUnit_Framework_TestCase{
    
    public function setUp(){
        
//id usuario 2
		
	}
            
   public function testInsertRequestRemoveConcept(){
       
       $id_project = include_project('Projeto_Projeto','Projeto_ProjetoDescricao');
       $id_concept = include_concept('2','123', "ana", '2', "remove", "0");
       
       try{
           insert_request_remove_concept($id_project, $id_concept, $id_user);
       }catch(Exception $e){
           $this->assertEquals('',$id_project);
       }
   }
}

?>
