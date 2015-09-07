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

interface CacheCalculadoraInterface {
    
    /**
     * Envia uma cache para a calculadora e ela retorna o tamanho em bits.
     * @param Cache $cache
     */
    public function calculaTamanhoCachePadrao(Cache $cache);
    public function calculaTamanhoCachePadraoParametrizado($address_size_bits, $tag_size_bits, $index_size_bits, $offset_size_bits, $control_size_bits, $ways_size);

    /**
     * Envia dados de uma cache porém sem índice explícito, para tentar calcular.
     * @param Cache $cache
     */
    public function calculaTamanhoCacheSemIndice(Cache $cache, $quantidade_blocos);
    public function calculaTamanhoCacheSemIndiceParametrizado($address_size_bits, $tag_size_bits, $offset_size_bits, $control_size_bits, $ways_size, $quantidade_blocos);
}


?>