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
class Cache
{

    public $address_size_bits, $control_size_bits, $tag_size_bits, $index_size_bits, $offset_size_bits, $ways_size;

    public function __construct($address_size_bits = 32, $control_size_bits = 1, $tag_size_bits = 13, $index_size_bits = 14, $offset_size_bits = 5, $ways_size = 1)
    {
        if ($address_size_bits != ($tag_size_bits + $index_size_bits + $offset_size_bits)) {
            throw new Exception("$address_size_bits is not equal to $tag_size_bits + $index_size_bits + $offset_size_bits");
        }
        $this->address_size_bits = $address_size_bits;
        $this->control_size_bits = $control_size_bits;
        $this->tag_size_bits = $tag_size_bits;
        $this->index_size_bits = $index_size_bits;
        $this->offset_size_bits = $offset_size_bits;
        $this->ways_size = $ways_size;
    }

    public function __toString()
    {
        return 'Configuração: ' . $this->address_size_bits . ' ' . $this->control_size_bits . ' ' . $this->tag_size_bits . ' ' . $this->index_size_bits . ' ' . $this->offset_size_bits . ' ' . $this->ways_size;
    }

    public function getTagSizeBits()
    {
        return $this->tag_size_bits;
    }

    public function setTagSizeBits($tag_size_bits)
    {
        if ($this->address_size_bits != ($tag_size_bits + $this->index_size_bits + $this->offset_size_bits))
            throw new Exception("$this->address_size_bits is not equal to $tag_size_bits + $this->index_size_bits + $this->offset_size_bits");
        $this->tag_size_bits = $tag_size_bits;
    }

    public function getIndexSizeBits()
    {
        return $this->index_size_bits;
    }

    public function setIndexSizeBits($index_size_bits)
    {
        if ($this->address_size_bits != ($this->tag_size_bits + $index_size_bits + $this->offset_size_bits))
            throw new Exception("$this->address_size_bits is not equal to $tag_size_bits + $this->index_size_bits + $this->offset_size_bits");
        $this->index_size_bits = $index_size_bits;
    }

    public function getOffsetSizeBits()
    {
        return $this->offset_size_bits;
    }

    public function setOffsetSizeBits($offset_size_bits)
    {
        if ($this->address_size_bits != ($this->tag_size_bits + $index_size_bits + $offset_size_bits))
            throw new Exception("$this->address_size_bits is not equal to $this->tag_size_bits + $this->index_size_bits + $offset_size_bits");
        $this->offset_size_bits = $offset_size_bits;
    }

    public function getAddressSizeBits()
    {
        return $this->address_size_bits;
    }

    public function setAddressSizeBits($address_size_bits)
    {
        if ($address_size_bits != ($this->tag_size_bits + $index_size_bits + $this->offset_size_bits))
            throw new Exception("$address_size_bits is not equal to $tag_size_bits + $this->index_size_bits + $this->offset_size_bits");
        $this->address_size_bits = $address_size_bits;
    }

    public function getControlSizeBits()
    {
        return $this->control_size_bits;
    }

    public function setControlSizeBits($control_size_bits = 1)
    {
        if ($control_size_bits > 0 && $control_size_bits < 3)
            $this->control_size_bits = $control_size_bits;
    }

    public function getWaysSize()
    {
        return $this->ways_size;
    }

    public function setWaysSize($ways_size = 1)
    {
        if ($ways_size % 2 == 0 and $ways >= 0 && $ways < 9)
            $this->ways_size = $ways_size;
    }
}

?>