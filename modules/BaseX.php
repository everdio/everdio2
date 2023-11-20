<?php

namespace Modules {

    trait BaseX {

        static public $_queries = [];

        final protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
                \CURLOPT_USERPWD => $this->username . ":" . $this->password]);
            return (object) $curl;
        }

        final public function getResponse(string $query): string {
            $this->setopt_array([
                \CURLOPT_URL => $this->host . \DIRECTORY_SEPARATOR . $this->database . \DIRECTORY_SEPARATOR . "?query=" . \urlencode($query)]);
            return (string) $this->execute();
        }

        final public function getDOMDocument(string $query): \DOMDocument {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($query), $this->root), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);

            return (object) $dom;
        }
    }

}