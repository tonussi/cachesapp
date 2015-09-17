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
require_once (dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'CacheCalculadoraInterface.php');

class CacheCalculadoraImpl implements CacheCalculadoraInterface
{

    public function __construct()
    {}

    /**
     * <p>Calcula o tamanho da cache, por linhas.</p>
     *
     * <p>Uma cache diretamente
     * mapeada, 2way, 3way, 4way, etc, tem n-way * pow(2, index bits size) linhas.</p>
     *
     * <p>Fullway cache não tem index, e o n-way da full-way cache é a quantidade
     * de conjuntos que a cache tem.</p>
     *
     * @see CacheCalculadoraInterface::calculaTamanhoCache()
     */
    public function calculaTamanhoCachePadrao(Cache $cache)
    {
        return $cache->getWaysSize() * pow(2, $cache->getIndexSizeBits()) * ($cache->getTagSizeBits() + $cache->getControlSizeBits() + (pow(2, $cache->getOffsetSizeBits()) << 3));
    }

    public function calculaTamanhoCachePadraoParametrizado($address_size_bits = 32, $tag_size_bits, $index_size_bits, $offset_size_bits, $control_size_bits, $ways_size)
    {
        return $ways_size * pow(2, $index_size_bits) * ($tag_size_bits + $control_size_bits + (pow(2, $offset_size_bits) << 3));
    }

    public function calculaTamanhoCacheSemIndice(Cache $cache, $quantidade_blocos)
    {
        if ($cache->getIndexSizeBits() > 0) {
            $sets = $quantidade_blocos / $cache->getWaysSize();
            return $quantidade_blocos * (($cache->getAddressSizeBits() - $cache->getOffsetSizeBits() - ceil(log($sets, 2))) + $cache->getControlSizeBits() + (pow(2, $cache->getOffsetSizeBits()) << 3));
        }
        return $quantidade_blocos * ($cache->getAddressSizeBits() - $cache->getOffsetSizeBits() + $cache->getControlSizeBits() + (pow(2, $cache->getOffsetSizeBits()) << 3));

    }

    public function calculaTamanhoCacheSemIndiceParametrizado($address_size_bits, $tag_size_bits, $offset_size_bits, $control_size_bits, $ways_size, $quantidade_blocos)
    {
        $sets = $quantidade_blocos / $ways_size;
        return $quantidade_blocos * (($address_size_bits - $offset_size_bits - ceil(log($sets, 2))) + $control_size_bits + (pow(2, $offset_size_bits) << 3));
    }
}

?>
