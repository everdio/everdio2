<?php
\libxml_use_internal_errors(false);

$xml = new \DOMDocument("1.0", "UTF-8");
$xml->preserveWhiteSpace = false;
$xml->formatOutput = false;
$xml->load($this->model->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);

$xpath = new \DOMXPath($xml);
foreach ($xpath->query("//*") as $node) {
    $model = new \Modules\Node\Xml\Model;
    $model->store($this->model->restore());
    $model->node = \dom_import_simplexml($node);
    $model->use = "\Modules\Node\Xml";        
    $model->setup();           
}