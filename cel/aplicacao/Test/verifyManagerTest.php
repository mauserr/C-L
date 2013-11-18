<?php
require_once dirname(__FILE__).'/../Functions/verifyManager.php';

class verifyManagerTest extends PHPUnit_Framework_TestCase{

	public function setUp(){

		$_SESSION['current_id_user'] = '6';
		ob_start();
		ob_get_clean();

	}
	
public function testverifyManagerCorret(){
	
	$id_project = include_project('Projetonlos',' ');
	$returnvalue = verifyManager($_SESSION['current_id_user'], $id_project['id_project']);

	$this->assertEquals('1',$returnvalue);

	removeProject($id_project);
	
}

public function testverifyManagerIncorret(){

	$id_project = '1';
	$returnvalue = verifyManager($_SESSION['current_id_user'], $id_project);

	$this->assertEquals('0',$returnvalue);

	removeProject($id_project);

}

}
?>