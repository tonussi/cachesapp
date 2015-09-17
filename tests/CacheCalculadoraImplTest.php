<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Description here.
 *
 * @package cachesapp
 * @copyright 2015 Universidade Federal de Santa Catarina {@link http://ufsc.br/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once (dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Cache.php');
require_once (dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'CacheCalculadoraImpl.php');

class CacheCalculadoraImplTest extends PHPUnit_Framework_TestCase
{

    public function testIfTagIsSettingOkay()
    {
        $cache = new Cache(36, 3, 13, 14, 9, 1);
        $this->assertEquals(13, $cache->getTagSizeBits());
    }

    public function testIfAddressIsSettingOkay()
    {
        $cache = new Cache(36, 3, 13, 14, 9, 1);
        $this->assertEquals(36, $cache->getAddressSizeBits());
    }

    public function testIfControlIsSettingOkay()
    {
        $cache = new Cache(36, 3, 13, 14, 9, 1);
        $this->assertEquals(3, $cache->getControlSizeBits());
    }

    public function testResultadoTamanhoEmBitsCacheDiretamenteMapeada()
    {
        $cache = new Cache(36, 3, 13, 14, 9, 1);
        $calculadora = new CacheCalculadoraImpl();
        // 1*2^14×(13+3+(2^5×8))
        $this->assertEquals(67371008, $calculadora->calculaTamanhoCachePadrao($cache));
    }

    public function testResultadoTamanhoEmBitsCacheTwoWay()
    {
        $cache = new Cache(36, 3, 13, 14, 9, 2);
        $calculadora = new CacheCalculadoraImpl();
        // 2*2^14×(13+3+(2^5×8))
        $this->assertEquals(134742016, $calculadora->calculaTamanhoCachePadrao($cache));
    }

    public function testResultadoTamanhoEmBitsCacheThreeWay()
    {
        $cache = new Cache(36, 1, 11, 16, 9, 3);
        $calculadora = new CacheCalculadoraImpl();
        // 3×2^16×(11+1+2^9×8)
        $this->assertEquals(807665664, $calculadora->calculaTamanhoCachePadrao($cache));
    }

    /**
     * Considere um sistema com as seguintes configurações:
     *
     * $2^{28}$ bytes endereçáveis de memória
     * Cache com $2^{5}$ blocos de $2^{7}$ bytes cada
     * Linhas de cache com 1 bit de validade
     *
     * Qual seria o tamanho efetivo da cache em bits caso ela
     * fosse implementada com um mapeamento 4-associativo?
     *
     * $\text{n-way} \times \frac{2^{5}}{4} \times((28-7-3)+1+2^{7}\times8)$
     *
     * $4 \times \frac{2^{5}}{4}\times(18+1+2^{7}\times8)$
     *
     * Então os parâmetros para a função seriam
     * endereço = 28, tag = 18,
     */
    public function testResultadoTamanhoEmBitsCacheFourWay()
    {
        $cache = new Cache(28, 1, 18, 3, 7, 4);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals(33376, $calculadora->calculaTamanhoCachePadrao($cache));
    }

    public function testSemIndiceExplicitoCacheFourWay()
    {
        $cache = new Cache(28, 1, 18, 3, 7, 4);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals($calculadora->calculaTamanhoCachePadrao($cache), $calculadora->calculaTamanhoCacheSemIndice($cache, 32));
    }

    /**
     * public function __construct ( $address_size_bits = 32, // (endereço)
     *                               $control_size_bits =  3, // (controle)
     *                               $tag_size_bits     = 21, // (tag)
     *                               $index_size_bits   =  0, // (full-assoc)
     *                               $offset_size_bits  = 11, // (offset)
     *                               $ways_size         = 16) // (ways)
     *
     * 16×(32−11+3+2^11×8) = 262528
     */
    public function testCalculoFullyAssociative16Blocos()
    {
        $cache = new Cache(32, 3, 21, 0, 11, 16);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals(262528, $calculadora->calculaTamanhoCacheSemIndice($cache, 16));
    }

    public function testCalculoFullyAssociative64Blocos()
    {
        $cache = new Cache(32, 3, 21, 0, 11, 64);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals(262528, $calculadora->calculaTamanhoCacheSemIndice($cache, 16));
    }

    /**
     * public function __construct ( $address_size_bits = 46, // (endereço)
     *                               $control_size_bits =  3, // (controle)
     *                               $tag_size_bits     = 21, // (tag)
     *                               $index_size_bits   = 11, // (full-assoc)
     *                               $offset_size_bits  = 14, // (offset)
     *                               $ways_size         = 1) // (ways)
     *
     * 2^11×(21+3+2^17) = 268484608
     */
    public function testCalculoPadrao46Bits21TagOneWay()
    {
        $cache = new Cache(46, 3, 21, 11, 14, 1);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals(268484608, $calculadora->calculaTamanhoCachePadrao($cache));
    }

    /**
     * public function __construct ( $address_size_bits = 46, // (endereço)
     *                               $control_size_bits =  3, // (controle)
     *                               $tag_size_bits     = 21, // (tag)
     *                               $index_size_bits   = 11, // (full-assoc)
     *                               $offset_size_bits  = 14, // (offset)
     *                               $ways_size         = 1) // (ways)
     *
     * 2^11×(21+3+2^17) = 268484608
     */
    public function testCalculoPadrao46Bits21TagTwoWay()
    {
        $cache = new Cache(46, 3, 21, 11, 14, 2);
        $calculadora = new CacheCalculadoraImpl();
        $this->assertEquals(536969216, $calculadora->calculaTamanhoCachePadrao($cache));
    }

}

?>
