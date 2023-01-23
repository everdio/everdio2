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
            if (!\array_key_exists(($key = $this->unique($this->inter(["appid", "lang", "units", "lat", "lon", "mode"]))), self::$_adapters)) {
                $xml = new \DOMDocument("1.0", "UTF-8");
                $xml->preserveWhiteSpace = false;
                $xml->formatOutput = false; 
                $xml->loadXML($this->execute(), \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                self::$_adapters[$key] = $xml;
            }
            
            return (object) self::$_adapters[$key];
        }      
    }
}