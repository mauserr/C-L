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

		$this->assertNotNull(TRUE,$object);
		removeProject($object);
	}

	public function testInsertProjectOnlyName(){

		$object = include_project('Projetonlos','');

		$this->assertNotNull(TRUE,$object);
		removeProject($object);
	}

	public function testInsertProjectWithoutName(){

		try{
			$object = include_project('','');
		}catch(Exception $e){
			$this->assertEquals('Preencha o campo "Nome"',$object);
		}


		removeProject($object);
	}
/*
	public function testRemoveProjectComplete(){

		$object = include_project('ProjetoTeste','ProjetoTeste pitchu');

		removeProject($object);

		$this->assertEquals('',$object);

	}
*/
}
?>