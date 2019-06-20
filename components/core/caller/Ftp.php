<?php
namespace Components\Core\Caller {
    class Ftp extends \Components\Core\Caller {
        public function __construct(string $url) {
            parent::__construct("ftp");
            $this->resource = $this->connect($url);
        }
        
        public function execute() : string {
            if (($response = $this->exec()) === false) {
                return (string) $this->error($this->curl);
            }
            
            return (string) trim($response);
        }
        
        public function __destruct() {
            $this->close($this->curl);
        }
    }
}