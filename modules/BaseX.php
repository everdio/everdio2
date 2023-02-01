<?php
namespace Modules {
    trait BaseX {        
        static public $_queries = [];
        
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                \CURLOPT_USERPWD => \sprintf("%s:%s", $this->username, $this->password), 
                \CURLOPT_ENCODING => "",
                \CURLOPT_RETURNTRANSFER => true]); 
            return (object) $curl;               
        }    
        
        final public function getDOMDocument(string $query) : \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->substituteEntities = false;  
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($query), $this->root), \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);                       
            return (object) $dom;
        }
        
        final public function getResponse(string $query) : string {
            //echo "<!-- query: " . $query . " -->" . \PHP_EOL;
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s/?query=%s", $this->host, $this->database, \urlencode($query)));
            return (string) $this->execute();
        }        
    }
}