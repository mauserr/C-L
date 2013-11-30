<?php
require_once dirname(__FILE__).'/../Functions/scenario_Functions.php';

class scenario_FunctionsTest extends PHPUnit_Framework_TestCase{


	public function setUp() {
		$_SESSION['current_id_user'] = '6';

		$_POST['id_project'] = '3';
		$_POST['title'] = 'teste';
		$_POST['objective'] = 'objective';
		$_POST['context'] = 'context';
		$_POST['actors'] = 'objective';
		$_POST['resource'] = 'resouce';
		$_POST['epidoses'] = 'epi';
		$_POST['exception'] = 'excep';

		//$id_project, $title, $objective, $context, $actors, $resources, $exception, $episodes
		ob_start();
		ob_get_clean();


	}
	public function tearDown(){

		$DB = new PGDB ();
		$sql1 = new QUERY ($DB);
		$id_project = '3';

		$sql1->execute ("DELETE FROM scenario WHERE id_project = $id_project") ;

	}

	public function testincludeScenario(){

		$scenario = include_Scenario($_POST['id_project'] = '3',
		$_POST['title'] = 'teste',
		$_POST['objective'] = 'objective',
		$_POST['context'] = 'context',
		$_POST['actors'] = 'objective',
		$_POST['resource'] = 'resouce',
		$_POST['epidoses'] = 'epi',
		$_POST['exception'] = 'excep');
		
		$this->assertNotNull(TRUE, $scenario);


	}
	public function testeincludeScenarioWrong(){

		$scenario = include_Scenario($_POST['id_project'] = '3',
				$_POST['title'] = 'teste',
				$_POST['objective'] = 'objective',
				$_POST['context'] = 'context',
				$_POST['actors'] = 'objective',
				$_POST['resource'] = 'resouce',
				$_POST['epidoses'] = 'epi',
				$_POST['exception'] = 'excep');
		
		//$scenario =	include_Scenario($id_project = '3', $title="Scenarioteste", $purpose="objectiveteste", $context="contextteste", $actors="actor1teste", $means="meansteste", $exception="excepteste", $episodes="eps 1teste");

		$this->assertNotNull(false, $scenario);
	}

/*	public function testremoveScenario(){


		$scenario = include_Scenario($_POST['id_project'] = '3',
		$_POST['title'] = 'teste',
		$_POST['objective'] = 'objective',
		$_POST['context'] = 'context',
		$_POST['actors'] = 'objective',
		$_POST['resource'] = 'resouce',
		$_POST['epidoses'] = 'epi',
		$_POST['exception'] = 'excep');
		
		$id_scenario = "SELECT id_scenario FROM scenario where id_project = 3"; 
		
		removeScenario($id_project = '3',$id_scenario);

		$scenario = null;
		$this->assertNull($scenario);
	}*/


}
?>
