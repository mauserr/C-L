<?php
require_once dirname(__FILE__).'/../Functions/project_Functions.php';

class project_FunctionsTest extends PHPUnit_Framework_TestCase{

	public function setUp(){

		$_SESSION['current_id_user'] = '6';
		ob_start();
		ob_get_clean();

	}

	public function testInsertProjectComplete(){

		$id_project = include_project('Projetonlos','ProjetonDescriptionlos');

		$this->assertNotNull(TRUE,$id_project);
		removeProject($id_project);
	}

	public function testInsertProjectOnlyName(){

		$id_project = include_project('Projesterona','');

		$this->assertNotNull(TRUE,$id_project);
		removeProject($id_project);
	}

	public function testInsertProjectWithoutName(){
		
		
		try{
			$id_project = include_project('','');
		}catch(Exception $e){
			$this->assertEquals('Preencha o campo "Nome"', $id_project);
		}


		removeProject($id_project);
	}
	
	public function testInsertProjectWrongName(){
	
		try{
			$id_project = include_project('//','Descrição');
		}catch(Exception $e){
			$this->assertEquals('O nome do projeto não pode conter nenhum dos seguintes caracteres:   / \\ : ? \" < > |',$id_project);
		}
	
		removeProject($id_project);
	}

	public function testRemoveProject(){

		$id_project = include_project('ProjetoTeste','ProjetoTeste pitchu');

		try{
			removeProject($id_project);
		}catch(Exception $e){
			$this->assertEquals('Projeto apagado com sucesso',$id_project);
		}
	}
	
	public function testeCheck_project_permanentWrong(){
		
		
		$id_project = '18';
		$this->assertEquals('0',check_project_permanent('2','20'));
	}
}
?>