<?php
namespace Modules {
    trait IpApi {
        protected function __init() : object {       
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true, 
                \CURLOPT_RETURNTRANSFER => true, 
                \CURLOPT_URL => \sprintf("%s/%s?fields=country,region,regionName,city,district,zip,lat,lon", $this->url, \urldecode($this->ip))]);
          
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->substituteEntities = false;  
            $dom->loadXML($curl->execute(), \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            
            return (object) $dom;
        }
    }
}