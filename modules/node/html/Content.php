<?php

namespace Modules\Node\Html {

    trait Content {

        use \Modules\Node;

        protected function addAdapter(): object {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadHTML($this->content, \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);
            return (object) $dom;
        }
    }

}