<?php
require_once dirname(__FILE__).'/../Functions/lexicon_functions.php';
class lexicon_functionsTest extends PHPUnit_Framework_TestCase {

    protected $idProjeto;
    protected $nome;
    protected $nocao;
    protected $impacto;
    protected $sinonimos;
    protected $classificacao;

    public function setUp() {
        $this->idProjeto= 1;
        $this->nome = "Nome Teste";
        $this->nocao = "Nocao teste";
        $this->impacto = "Impacto teste";
        $this->sinonimos = "Sinonimos teste";
        $this->classificados = "CLassificados teste";
    }

    /**
     * @test
     *
     */
    public function testIncluiLexico() {
        $retorno = inclui_lexico($this->idProjeto,$this->nome,$this->nocao,$this->impacto,$this->sinonimos,$this->classificados);
        $this->assertNotNull($retorno);
        
    }
    
    /**
     * @test
     *
     */
    public function testRemoveLexico() {
        $this->assertNotNull(removeLexico($this->idProjeto, 1));
        
    }

}

?>
