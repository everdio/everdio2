<?php
namespace Modules {
    trait BaseX {        
        final protected function initialize() : object {
            if (!\array_key_exists(($key = $this->unique($this->diff())), self::$_adapters)) {
                $curl = new \Component\Caller\Curl;
                $curl->setopt_array([
                    \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                    \CURLOPT_USERPWD => \sprintf("%s:%s", $this->username, $this->password), 
                    \CURLOPT_RETURNTRANSFER => true
                    ]); 
                
                self::$_adapters[$key] = $curl;
            }

            return (object) self::$_adapters[$key];
        }            
  
        final public function query(string $query) : string {
            //echo "<!-- raw: " . $query . "-->" . \PHP_EOL;
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s/?query=%s", $this->host, $this->database, \urlencode($query)));
            return (string) $this->execute();
        }           
        
        final public function post(string $content) {
            $curl = $this->initialize();
            $curl->post($content);
            $curl->setopt(\CURLOPT_URL, $this->host);
            $curl->execute();             
        }
        
        final public function put($handle, int $size = 0) {
            $curl = $this->initialize();
            $curl->put($handle, $size);
            $curl->setopt(\CURLOPT_URL, $this->host);
            $curl->execute();    
        }          
    }
}