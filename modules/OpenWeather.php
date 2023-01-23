<?php
namespace Modules {
    trait OpenWeather {
        
        protected function initialize() : object {       
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true, 
                \CURLOPT_RETURNTRANSFER => true, 
                \CURLOPT_URL => \sprintf("%s?%s", $this->url, \urldecode($this->querystring(["appid", "lang", "units", "lat", "lon", "mode"])))]);
            return (object) $curl;
        }        

        final public function fetch() : \DOMDocument {
            if (!\array_key_exists($this->unique($this->diff()), self::$_adapters)) {
                $xml = new \DOMDocument("1.0", "UTF-8");
                $xml->preserveWhiteSpace = false;
                $xml->substituteEntities = false;
                $xml->formatOutput = false; 
                $xml->recover = true;
                $xml->loadXML($this->execute(), \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                
                self::$_adapters[$this->unique($this->diff())] = $xml;
            }
            
            return (object) self::$_adapters[$this->unique($this->diff())];
        }      
    }
}