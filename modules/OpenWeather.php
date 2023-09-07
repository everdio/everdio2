<?php

namespace Modules {

    trait OpenWeather {

        final protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_URL => \sprintf("%s?%s", $this->url, \urldecode($this->querystring(["appid", "lang", "units", "lat", "lon", "mode"])))]);

            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML($curl->execute(), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);
            return (object) $dom;
        }
    }

}