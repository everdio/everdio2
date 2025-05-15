<?php

namespace Modules\Node\Xml {

    trait Url {

        use \Modules\Node;

        protected function addAdapter(): object {
            $curl = new \Component\Caller\Curl\Client;
            $curl->setopt_array([
                \CURLOPT_URL => $this->url]);

            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->load($curl->execute(), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);

            return (object) $dom;
        }
    }

}