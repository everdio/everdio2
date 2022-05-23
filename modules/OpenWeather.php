<?php
namespace Modules {
    trait OpenWeather {
        public function initialize() {            
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([CURLOPT_FOLLOWLOCATION => true, 
                                CURLOPT_RETURNTRANSFER => true, 
                                CURLOPT_URL => sprintf("%s?%s", $this->url, urldecode(http_build_query($this->restore(["appid", "lang", "units", "lat", "lon", "mode"]))))]);                       
            return (object) $curl;
        }
        
        final public function fetch() : \DOMDocument {
            if (!\array_key_exists($this->unique($this->diff()), self::$adapters)) {
                $dom = new \DOMDocument;
                $dom->loadXML($this->execute(), LIBXML_HTML_NOIMPLIED | LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING);
                self::$adapters[$this->unique($this->diff())] = $dom;
            }
            
            return (object) self::$adapters[$this->unique($this->diff())];
        }      
    }
}