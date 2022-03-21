<?php
namespace Component\Core\Caller {
    class Ftp extends \Component\Core\Caller {
        public function __construct(string $url) {
            parent::__construct("ftp_");
            $this->resource = $this->connect($url);
        }
        
        final public function execute() : string {
            if (($response = $this->exec()) === false) {
                return (string) $this->error();
            }
            
            return (string) \trim($response);
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}