<?php
namespace Modules {
    trait BaseX {
        static public $_queries = [];
        static public $_queries2 = [];
        
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_RETURNTRANSFER => true,      
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                \CURLOPT_USERPWD => $this->username . ":" . $this->password]); 
            return (object) $curl;
        }

        final public function getResponse(string $query) : string {
            echo "<!-- query: " . $query . " -->" . \PHP_EOL;
            $this->setopt_array([
                \CURLOPT_URL => $this->host . \DIRECTORY_SEPARATOR . $this->database . \DIRECTORY_SEPARATOR . "?query=" . \urlencode($query),
                \CURLOPT_CUSTOMREQUEST => "GET"]);
            return (string) $this->execute();            
        }

        final public function getDOMDocument(string $query) : \DOMDocument {     
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($query), $this->root), \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_NOBLANKS | \LIBXML_NOENT);
            return (object) $dom;
        }        
    }
}