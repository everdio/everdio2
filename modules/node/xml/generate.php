<?php
$xml = new \DOMDocument("1.0", "UTF-8");
$xml->preserveWhiteSpace = false;
$xml->formatOutput = false; 
$xml->load($this->model->document, \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);

$xpath = new \DOMXPath($xml);
foreach ($xpath->query("//*") as $node) {

    $model = new \Modules\Node\Xml\Model;
    $model->store($this->model->restore());
    $model->node = $node;
    $model->use = "\Modules\Node\Xml";        
    $model->setup();          
}