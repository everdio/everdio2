<?php

namespace Modules {

    trait BaseX {

        static public $_queries = [];

        final protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
                \CURLOPT_USERPWD => $this->basex_username . ":" . $this->basex_password]);
            
            return (object) $curl;
        }

        public function getResponse(string $query): string {
            $time = \microtime(true);
            
            $this->setopt(\CURLOPT_URL, $this->basex_host . \DIRECTORY_SEPARATOR . $this->basex_database . \DIRECTORY_SEPARATOR . "?query=" . \urlencode($query));
            
            $response = $this->execute(); 
            
            if (isset($this->basex_log)) {
                \file_put_contents($this->basex_log, \sprintf("%s\t%s\t%s\n", \date("Y-m-d H:i:s"), \round(\microtime(true) - $time, 5), $query), \FILE_APPEND);
            }
            
            return (string) $response;
        }

        public function getDOMDocument(string $query): \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($query), $this->root), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);

            return (object) $dom;
        }
    }

}