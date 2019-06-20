<?php
namespace Components\Parser {
    class Csv extends \Components\Parser {
        const EXTENSION = ".csv";  
        
        /*
         * thanks to starrychloe at oliveyou dot net
         * http://php.net/manual/en/function.str-getcsv.php#117692
         */
        static public function parse(string $content) : array {
            $csv = array_map('str_getcsv', $content);
            
            array_walk($csv, function(&$data) use ($csv) {
                $data = array_combine($csv[0], $data);
            });
            
            array_shift($csv);
            
            return (array) $csv;
        }
    }
}


