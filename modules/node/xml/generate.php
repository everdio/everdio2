<?php
$xml = new \DOMDocument("1.0", "UTF-8");
$xml->preserveWhiteSpace = false;
$xml->substituteEntities = false;
$xml->formatOutput = false; 
$xml->recover = true;
$xml->load($this->model->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);

$xpath = new \DOMXPath($xml);
foreach ($xpath->query("//*") as $node) {

    $model = new \Modules\Node\Xml\Model;
    $model->store($this->model->restore());
    $model->node = $node;
    $model->use = "\Modules\Node\Xml";        
    $model->setup();          
}