<?php

namespace Component\Caller\Curl {

    class Client extends \Component\Caller\Curl {

        public function __construct(array $headers = []) {            
            parent::__construct();
            
            $this->setopt_array([
                \CURLOPT_HTTPHEADER => \array_map(function($field, $value){ return \sprintf("%s: %s", $field, $value); }, \array_keys($headers), \array_values($headers)),
                \CURLOPT_ENCODING => "gzip, deflate",
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_AUTOREFERER => true,
                //\CURLOPT_VERBOSE => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_TIMEOUT => 9,                
                \CURLOPT_COOKIESESSION => true,
                \CURLOPT_CUSTOMREQUEST => "GET"
            ]);
        }
    }

}