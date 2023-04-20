<?php
namespace Modules {
    trait BaseX {
        static public $_queries = [];
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true, 
                \CURLOPT_RETURNTRANSFER => true,      
                \CURLOPT_TCP_FASTOPEN => true,
                \CURLOPT_SSL_VERIFYPEER => false,                
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC, 
                \CURLOPT_USERPWD => $this->username . ":" . $this->password,
                \CURLOPT_ENCODING => ""]); 
            return (object) $curl;
        }   
        
        final public function fromCurl(string $query) : string {
            $this->setopt_array([
                \CURLOPT_URL => $this->host . \DIRECTORY_SEPARATOR . $this->database . \DIRECTORY_SEPARATOR . "?query=" . \urlencode($query),
                \CURLOPT_CUSTOMREQUEST => "GET"]);
            return (string) $this->execute();            
        }
        
        final public function fromMemcached(string $query, int $ttl = 3600, bool $debug = false) : string {
            if ($debug) {
                echo "<!-- query: " . $query . " -->" . \PHP_EOL;                
                return (string) $this->fromCurl($query);
            } else {
                $memcached = new \Memcached($this->database);
                $memcached->setOption(\Memcached::OPT_PREFIX_KEY, "basex_query_");
                $memcached->setOption(\Memcached::OPT_COMPRESSION, true);

                if (empty($memcached->getServerList())) {
                    $memcached->addServer("127.0.0.1", 11211);
                }            

                $md5 = \md5($query);
                if (!$memcached->get($md5) && $memcached->getResultCode() !== 0) {
                    $memcached->add($md5, (string) $this->fromCurl($query), $ttl);
                }

                return (string) $memcached->get($md5);
            }
        }        
        
        final public function getDOMDocument(string $query) : \DOMDocument {     
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->substituteEntities = false;  
            $dom->loadXML("<" . $this->root . ">" . $this->fromMemcached($query) . "</" . $this->root . ">", \LIBXML_PARSEHUGE | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            return (object) $dom;
        }        
    }
}