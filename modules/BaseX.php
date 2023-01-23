<?php
namespace Modules {
    trait BaseX {        
        final protected function initialize() : object {
            if (!\array_key_exists($this->host . $this->database, self::$_adapters)) {            
                $curl = new \Component\Caller\Curl;
                $curl->setopt_array([
                    \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                    \CURLOPT_USERPWD => \sprintf("%s:%s", $this->username, $this->password), 
                    \CURLOPT_RETURNTRANSFER => true]); 
                self::$_adapters[$this->host . $this->database] = $curl;                
            }
            
            return (object) self::$_adapters[$this->host . $this->database];
        }            
  
        final public function query(string $query) : string {
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s/?query=%s", $this->host, $this->database, \urlencode($query)));
            return (string) $this->execute();
        }           
        
        final public function post(string $content) {
            $curl = $this->initialize();
            $curl->post($content);
            $curl->setopt_array([
                \CURLOPT_URL => $this->host,
                \CURLOPT_HTTPHEADER => ["Content-Type: application/xml"]]);
            $curl->execute();
        }
        
        final public function put($handle, int $size = 0) {
            $curl = $this->initialize();
            $curl->put($handle, $size);
            $curl->setopt_array([
                \CURLOPT_URL => $this->host,
                \CURLOPT_HTTPHEADER => ["Content-Type: application/xml"]]);
            $curl->execute();    
        }          
    }
}