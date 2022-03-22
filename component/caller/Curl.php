<?php
namespace Component\Caller {
    class Curl extends \Component\Caller {
        public function __construct() {
            parent::__construct("curl_");
            $this->resource = $this->init();
        }
        
        final public function put(File\Fopen $fopen) {
            $this->setopt_array([
                \CURLOPT_INFILE => $fopen->getResource(),
                \CURLOPT_INFILESIZE => $fopen->getSize(),
                \CURLOPT_PUT => true,
                \CURLOPT_BINARYTRANSFER => true,
                \CURLOPT_HEADER => false
            ]);    
            
            $fopen->seek(0);
        }
        
        final public function post(string $content) {
            $this->setopt_array([
                \CURLOPT_POSTFIELDS => $content,
                \CURLOPT_INFILESIZE => \strlen($content),
                \CURLOPT_POST => true
            ]);            
        }

        final public function execute() {
            if (($response = $this->exec()) === false) {
                throw new \RuntimeException($this->error());
            }
            
            return (string) \trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }
}