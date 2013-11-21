<?php
require_once dirname(__FILE__).'/../Functions/verifyManager.php';

class verifyManagerTest extends PHPUnit_Framework_TestCase{

	public function setUp(){

		$_SESSION['current_id_user'] = '6';
		ob_start();
		ob_get_clean();

	}
	
public function testverifyManagerCorret(){
	
	$insert_sql = "INSERT INTO participates (id_user, id_project)
	VALUES ('100, " . '20' . ")";
	$update_sql = "UPDATE participates
	SET manager = 1
	WHERE id_user= 100 AND id_project = 20 ";
	mysql_query($insert_sql);
	mysql_query($update_sql);
	
	$returnvalue = verifyManager(100, 20);

	$this->assertEquals('0',$returnvalue);

	$remove_sql = "DELETE FROM participates WHERE id_user = 100";
	
}

public function testverifyManagerIncorret(){

	$id_project = '1';
	$returnvalue = verifyManager($_SESSION['current_id_user'], $id_project);

	$this->assertEquals('0',$returnvalue);


}

}
?>