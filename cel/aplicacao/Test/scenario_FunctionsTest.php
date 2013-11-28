<?php
require_once dirname(__FILE__).'/../Functions/scenario_Functions.php';

class scenario_FunctionsTest extends PHPUnit_Framework_TestCase{

	public function setUp() {
		$_SESSION['current_id_user'] = '6';
		ob_start();
		ob_get_clean();
		

	}

	public function testinclude_Scenario(){
	
		$scenario =	include_Scenario($id_project = '3', $title="Scenarioteste", $purpose="objectiveteste", $context="contextteste", $actors="actor1teste", $means="meansteste", $exception="excepteste", $episodes="eps 1teste");
		$this->assertNotNull(TRUE, $scenario);
	}
}
?>
