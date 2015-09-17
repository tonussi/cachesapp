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
class SQLCacheInsertionsTest extends PHPUnit_Extensions_Database_TestCase
{

    public $fixtures = array(
        'caches',
        'usuarios',
        'usuarios_caches'
    );

    protected $conn = null;

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=cachesapp', '', '');
                $this->conn = $this->createDefaultDBConnection($pdo, 'cachesapp');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $this->conn;
    }

    public function getDataSet()
    {
        $fixtures = $this->fixtures;
        $compositeDs = new PHPUnit_Extensions_Database_DataSet_CompositeDataSet(array());
        $fixturePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixtures';
        foreach ($fixtures as $fixture) {
            $xmlFile = $fixturePath . DIRECTORY_SEPARATOR . "$fixture.xml";
            $ds = $this->createFlatXmlDataSet($xmlFile);
            $compositeDs->addDataSet($ds);
        }
        return $compositeDs;
    }

    public function loadDataSet($dataSet)
    {
        // set the new dataset
        $this->getDatabaseTester()->setDataSet($dataSet);
        // call setUp which adds the rows
        $this->getDatabaseTester()->onSetUp();
    }

    public function setUp()
    {
        $conn = $this->getConnection();
        $pdo = $conn->getConnection();
        // set up tables
        $fixtureDataSet = $this->getDataSet($this->fixtures);
        foreach ($fixtureDataSet->getTableNames() as $table) {
            // drop table
            $pdo->exec("DROP TABLE IF EXISTS `$table`;");
            // recreate table
            $meta = $fixtureDataSet->getTableMetaData($table);
            $create = "CREATE TABLE IF NOT EXISTS `$table` ";
            $cols = array();
            foreach ($meta->getColumns() as $col) {
                $cols[] = "`$col` VARCHAR(200)";
            }
            $create .= '(' . implode(',', $cols) . ');';
            $pdo->exec($create);
        }

        parent::setUp();
    }

    public function tearDown()
    {
        $allTables = $this->getDataSet($this->fixtures)->getTableNames();
        foreach ($allTables as $table) {
            // drop table
            $conn = $this->getConnection();
            $pdo = $conn->getConnection();
            $pdo->exec("DROP TABLE IF EXISTS `$table`;");
        }

        parent::tearDown();
    }

    function testReadDatabase()
    {
        $conn = $this->getConnection()->getConnection();

        // fixtures auto loaded, let's read some data
        $query = $conn->query('SELECT * FROM caches');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(5, count($results));

        // now reload them
        $ds = $this->getDataSet(array(
            'caches'
        ));

        $this->loadDataSet($ds);

        $query = $conn->query('SELECT * FROM caches');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(5, count($results));
    }
}
?>
