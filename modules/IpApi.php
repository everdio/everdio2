<?php

namespace Modules {

    trait IpApi {

        protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_RETURNTRANSFER => true]);
            
            return (object) $curl;
        }
        
        public function getResponse(): string {
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s", $this->url, \urldecode($this->ip)));
            return (string) $this->execute(); 
        }

        public function getDOMDocument(): \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML($this->getResponse(), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);
            return (object) $dom;
        }        
    }

}