<?php
require_once dirname(__FILE__).'/../Functions/project_Functions.php';

class project_FunctionsTest extends PHPUnit_Framework_TestCase{
	
	function setUp(){
		
		$_POST['name'] = 'wilker';
		$_POST['email'] = 'wilker@mail.com';
		$_POST['password'] = '123456';
		  
	}
	public function testinclude_project(){
		$id_project = include_project("Projeto","Descriчуo");
		
		$this->assertNotNull($id_project);
	}
}
?>