<?php
namespace Modules {
    trait Bingmaps {
        public function initialize() {            
            if (!\array_key_exists($this->unique($this->diff()), self::$_adapters)) {
                $curl = new \Component\Caller\Curl;
                $curl->setopt_array([
                    \CURLOPT_FOLLOWLOCATION => true, 
                    \CURLOPT_RETURNTRANSFER => true]);
                self::$_adapters[$hash] = $curl;
            }
            
            return (object) self::$_adapters[$hash];
        }
        
        final public function fetch(string $query) : string {
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s", $this->url, \urlencode($query)));
            return (string) $this->execute();
        }        

        final public function fetchDOM(string $query) : \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->fetch($query), $this->root), \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            return (object) $dom;
        }
    }
}