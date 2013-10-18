<?php
require_once dirname(__FILE__).'/../Functions/project_Functions.php';

class project_FunctionsTest extends PHPUnit_Framework_TestCase{
	
public function setUp(){
	
	$_SESSION['id_usuario_corrente'] = '2';
	ob_start();
	ob_get_clean();
	
}
	
public function testInsertProjectComplete(){
	
	$object = include_project('Projetonlos','ProjetonDescriptionlos');
	
	$this->assertNotNull($object);
	removeProject($object);
}

public function testRemoveProjectComplete(){
	
	$object = include_project('ProjetoTeste','ProjetoTeste pitchu');
	echo $object;
	$object = removeProject($object);
	$this->assertNotNull(FALSE, $object);
	
}
	
}
?>