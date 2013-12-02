<?php
require_once dirname(__FILE__).'/../Functions/concept_Functions.php';
require_once dirname(__FILE__).'/../Functions/project_Functions.php';

class concept_FunctionsTeste extends PHPUnit_Framework_TestCase{
    
    protected $id_concept;
    protected $name;
    protected $description;
    protected $namespace;
    protected $synonymous;
    protected $current_id_user;
    
    public function setUp(){
        
        $this->id_concept= 1;
        $this->name = "Name Test";
        $this->description = "Description Test";
        $this->namespace = "Namespace Test";
        $this->justification = "Synonymous Test";
        $this->current_id_user = "Classification Test";
       	
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
