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

class CacheTest extends PHPUnit_Framework_TestCase
{

    public function testMustNotBeEqual()
    {
        $objectA = new Cache(32, 1, 13, 14, 5, 1);
        $objectB = new Cache(36, 1, 13, 14, 9, 1);
        $this->assertNotEquals($objectA, $objectB);
    }

    public function testEqualityForTags()
    {
        $objectA = new Cache(32, 2, 13, 14, 5, 1);
        $objectB = new Cache(36, 2, 13, 14, 9, 1);
        $this->assertEquals($objectA->getTagSizeBits(), $objectB->getTagSizeBits());
    }

    public function testCacheCreationMustFail()
    {
        $this->assertNotEquals(null, new Cache());
    }
}

?>
