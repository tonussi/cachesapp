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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Cache.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'CacheCalculadoraImpl.php';

// $h is the cleaned value of $_POST["hash"]

if (isset($_SESSION["hash"]) && is_array($_SESSION["hash"])) {
    if (in_array($h, $_SESSION["hash"])) {
        // duplicate form submission
        /* REDIRECT SOMEWHERE HERE, PREFERABLY WITH SOME SORT OF MESSAGE! */
        http_response_code(403);
        echo "Ouve um problema, tente novamente.";
    } else {
        // add this hash to the array
        if (sizeof($_SESSION["hash"]) > 4) {
            array_shift($_SESSION["hash"]);
        }
        array_push($_SESSION["hash"], $h);
    }
} else {
    // create a hash array and add this hash
    $_SESSION["hash"] = array(
        $h
    );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $address_size_bits = filter_var($_POST["address_size_bits"], FILTER_VALIDATE_INT);
    // echo 'Address' . $address_size_bits;
    $control_size_bits = filter_var($_POST["control_size_bits"], FILTER_VALIDATE_INT);
    // echo 'Control: ' . $control_size_bits;
    $tag_size_bits = filter_var($_POST["tag_size_bits"], FILTER_VALIDATE_INT);
    // echo 'Tag: ' . $tag_size_bits;
    $index_size_bits = filter_var($_POST["index_size_bits"], FILTER_VALIDATE_INT);
    // echo 'Index: ' . $index_size_bits;
    $offset_size_bits = filter_var($_POST["offset_size_bits"], FILTER_VALIDATE_INT);
    // echo 'Offset: ' . $offset_size_bits;
    $way_size = filter_var($_POST["way_size"], FILTER_VALIDATE_INT);
    // echo 'Nway: ' . $way_size;
    
    foreach ($_POST as $value) {
        if ($value < 0 or $value == null) {
            http_response_code(400);
            echo "$value precisa ser um valor inteiro, não nulo e não negativo.";
            exit();
        }
    }
    
    try {
        
        $cache = new Cache($address_size_bits, $control_size_bits, $tag_size_bits, $index_size_bits, $offset_size_bits, $way_size);
        
        $calculadora = new CacheCalculadoraImpl();
        
        $retorno = $calculadora->calculaTamanhoCachePadrao($cache);
        
        $message = "Tamanho Total da Cache: " . $retorno . " (bits) = " . ($retorno / 1024 / 8) . " (KB)";

        // $message .= "\n\t" . json_encode($cache, JSON_PRETTY_PRINT) . "\n";
        
        echo $message;
        
        // echo ' Tamanho em bits: ' . $calculadora->calculaTamanhoCachePadraoParametrizado($cache->getAddressSizeBits(), $cache->getTagSizeBits(), $cache->getIndexSizeBits(), $cache->getOffsetSizeBits(), $cache->getControlSizeBits(), $cache->getWaysSize());
    } catch (Exception $e) {
        http_response_code(400);
        echo "Exceção de Configuração da Cache: " . $e->getMessage() . ". Você precisa apenas ajustar suas configurações para calcular corretamente o tamanho da cache.";
        exit();
    }
} else {
    http_response_code(403);
    echo "Ouve um problema, tente novamente.";
}

?>
