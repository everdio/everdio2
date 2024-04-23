<?php

namespace Modules\Node\Html {

    trait Url {

        use \Modules\Node;

        protected function __init(): object {
            $curl = new \Component\Caller\Curl\Client;
            $curl->setopt_array([
                \CURLOPT_TIMEOUT => 6,
                \CURLOPT_AUTOREFERER => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_URL => $this->url]);

            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadHTML($curl->execute(), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);

            return (object) $dom;
        }
    }

}