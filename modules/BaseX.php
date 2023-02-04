<?php
namespace Modules {
    trait BaseX {        
        static public $_queries = [];
        
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true, 
                \CURLOPT_RETURNTRANSFER => true,                 
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                \CURLOPT_USERPWD => \sprintf("%s:%s", $this->username, $this->password), 
                \CURLOPT_ENCODING => "",                
                \CURLOPT_TCP_FASTOPEN => true,
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_IPRESOLVE => \CURL_IPRESOLVE_V4]); 
            return (object) $curl;
        }    
        
        final public function getDOMDocument(string $query) : \DOMDocument {         
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->substituteEntities = false;  
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($query), $this->root), \LIBXML_PARSEHUGE | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);                       
            return (object) $dom;
        }
        
        final public function getResponse(string $query) : string {
            $memcache = new \Memcached("basex");
            if (!\sizeof($memcache->getServerList())) {
                $memcache->addServer("127.0.0.1", 11211);
            }            

            if (!$memcache->get(\md5($query))) {
                $this->setopt_array([
                    \CURLOPT_URL => \sprintf("%s/%s/?query=%s", $this->host, $this->database, \urlencode($query)),
                    \CURLOPT_CUSTOMREQUEST => "GET"]);
                $memcache->set(\md5($query), $this->execute(), 300);
            }

            return (string) $memcache->get(\md5($query));
        } 
        
        /*
        final public function getResponse(string $query) : string {
            //echo "<!-- query: " . $query . " -->" . \PHP_EOL;
            $this->setopt_array([
                \CURLOPT_URL => \sprintf("%s/%s/?query=%s", $this->host, $this->database, \urlencode($query)),
                \CURLOPT_CUSTOMREQUEST => "GET"]);
            return (string) $this->execute();            
        }
         * 
         */
    }
}