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

class SQLCacheInsertionsTest extends PHPUnit_Framework_TestCase
{

    public function testTableDropping()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "drop table if exists caches cascade;
                          drop table if exists usuarios cascade;";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }

    public function testTableCacheCreation()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "create table caches(id serial not null,
                            address_size_bits integer not null,
                            tag_size_bits integer not null,
                            indice_size_bits integer not null,
                            offset_size_bits integer not null,
                            ways integer not null,
                            hash bytea,
                            constraint caches_pkey primary key (id),
                            constraint caches_ways_check check (ways = any (array[1, 2, 3, 4, 5, 6, 7, 8])));
                          create index caches_idx on \"caches\" (id);";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }

    public function testTableUsersCreation()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "create table usuarios(id serial not null,
                            nome character varying(60) not null,
                            codcaches integer,
                            constraint usuarios_pkey primary key (id),
                            constraint codcaches_fkey foreign key (codcaches)
                            references caches (id) match simple on update no action on delete no action,
                            constraint usuarios_nome_check check (nome::text <> ''::text)));
                          create index material_idx on \"usuarios\" (id);";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }

    public function testTriggerCreation()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "create or replace function hash_update_tg() returns trigger as \$hash_update_tg\$
                            declare juntadados text;
                          begin
                            if tg_op = 'insert' or tg_op = 'update' then
                              juntadados = concat(new.address_size::text,
                                                  new.tag_size_bits::text,
                                                  new.indice_size_bits::text,
                                                  new.offset_size_bits::text);
                              new.hash = digest(juntadados, 'sha256');
                            end if;
                              return new;
                            end;
                          \$hash_update_tg\$ language plpgsql;
                          create trigger caches_hash_update before insert or update
                            on caches for each row execute procedure hash_update_tg();";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }

    public function testTableCacheInsertion()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "insert into caches(id, address_size, tag_size_bits, indice_size_bits, offset_size_bits, ways) VALUES (0, 32, 13, 9, 11, 2);";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }

    public function testGettingDataFromTableCaches()
    {
        try {
            $dbname = 'dbcaches';
            $user = 'lucastonussi';
            $password = 'postgres';
            $host = 'localhost';
            $pdo = new PDO("pgsql:dbname=$dbname;host=$host", $user, $password);
            $statement = "select * from table caches;";
            $pdo->query($statement);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $pdo = null;
    }
}
?>
