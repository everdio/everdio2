<?php
namespace Modules {
    trait BaseX {
        protected function initialize() {
            if (!\array_key_exists(($hash = md5($this->host)), self::$_adapters)) {
                $curl = new \Component\Caller\Curl;
                $curl->setopt_array([
                    \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                    \CURLOPT_USERPWD => \sprintf("%s:%s", $this->username, $this->password), 
                    \CURLOPT_RETURNTRANSFER => true
                    ]); 
                
                self::$_adapters[$hash] = $curl;
            }

            return (object) self::$_adapters[$hash];
        }
        
        final public function fetch(string $query) : string {    
            $this->setopt(\CURLOPT_URL, \sprintf("%s?query=%s", $this->host, \urlencode($query)));
            return (string) $this->execute();
        }        

        final public function fetchDOM(string $query) : \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->fetch($query), $this->root), \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            return (object) $dom;
        }

        final public function post(string $content) {
            $curl = $this->initialize();
            $curl->post($content);
            $curl->setopt(\CURLOPT_URL, $this->host);
            $curl->execute();             
            $curl->close();
        }
        
        final public function put($handle, int $size = 0) {
            $curl = $this->initialize();
            $curl->put($handle, $size);
            $curl->setopt(\CURLOPT_URL, $this->host);
            $curl->execute();    
            $curl->close();
        }          
    }
}