<?php
namespace Modules {
    trait OpenWeather {
        public function initialize() {            
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true, 
                \CURLOPT_RETURNTRANSFER => true, 
                \CURLOPT_URL => sprintf("%s?%s", $this->url, \urldecode(\http_build_query($this->restore(["appid", "lang", "units", "lat", "lon", "mode"]))))]);
            return (object) $curl;
        }
        
        final public function fetch() : \DOMDocument {
            if (!\array_key_exists($this->unique($this->diff()), self::$_adapters)) {
                $xml = new \DOMDocument("1.0", "UTF-8");
                $xml->preserveWhiteSpace = false;
                $xml->formatOutput = false;
                $xml->loadXML($this->execute(), \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);                
                
                self::$_adapters[$this->unique($this->diff())] = $xml;
            }
            
            return (object) self::$_adapters[$this->unique($this->diff())];
        }      
    }
}