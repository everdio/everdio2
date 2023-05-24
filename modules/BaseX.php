<?php
namespace Modules {
    trait BaseX {
        static public $_queries = [];
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_RETURNTRANSFER => true,      
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                \CURLOPT_USERPWD => $this->username . ":" . $this->password]); 
            return (object) $curl;
        }

        final public function getResponse(string $query) : string {
            echo "<!--raw " . $query . "-->" . \PHP_EOL;
            $this->setopt_array([
                \CURLOPT_URL => $this->host . \DIRECTORY_SEPARATOR . $this->database . \DIRECTORY_SEPARATOR . "?query=" . \urlencode($query),
                \CURLOPT_CUSTOMREQUEST => "GET"]);
            return (string) $this->execute();            
        }
        
        final public function getMemcachedResponse(string $query, int $ttl = 3600) : string {
            $memcached = new \Memcached($this->database);
            $memcached->setOption(\Memcached::OPT_COMPRESSION, true);
            if (empty($memcached->getServerList())) {
                $memcached->addServer("127.0.0.1", 11211);
            }            

            if ((!$response = $memcached->get(\md5($query))) && $memcached->getResultCode() !== 0) {
                $response = $this->getResponse($query);
                $memcached->add(\md5($query), $response, $ttl);
                return (string) $response;
            }
            
            return (string) $response;
        }        
        
        final public function getDOMDocument(string $query) : \DOMDocument {     
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->substituteEntities = false;  
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getMemcachedResponse($query), $this->root), \LIBXML_PARSEHUGE | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);                       
            return (object) $dom;
        }        
    }
}