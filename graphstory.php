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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Neoxygen\NeoClient\ClientBuilder;

$url = parse_url(getenv('GRAPHSTORY_URL'));

$client = ClientBuilder::create()->addConnection('default', $url['scheme'], $url['host'], $url['port'], true, $url['user'], $url['pass'])->build();

?>