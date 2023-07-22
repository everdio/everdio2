<?php
namespace Modules\Node {
    trait Xml {
        use \Modules\Node;
        protected function __init() : object {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->load($this->document, \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_NOBLANKS | \LIBXML_NOENT);
            return (object) $dom;
        }
    }
}