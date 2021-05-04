<?php
namespace Component\Parser {
    final class Csv extends \Component\Parser {
        const EXTENSION = "csv";  
        
        /*
         * thanks to starrychloe at oliveyou dot net
         * http://php.net/manual/en/function.str-getcsv.php#117692
         */
        final static public function parse(string $content, string $delimiter = ";", string $enclosure = "\"", string $escape = "\\", array $csv = []) : array {
            foreach (explode(PHP_EOL, $content) as $key => $row) {
                if ($key === 0) {
                    $columns = str_getcsv(strtolower($row), $delimiter, $enclosure, $escape);
                }
                $csv[] = array_combine($columns, str_getcsv($row, $delimiter, $enclosure, $escape));
            }            
            return (array) $csv;
        }
    }
}


