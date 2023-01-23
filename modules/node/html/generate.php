<?php
$html = new \DOMDocument("1.0", "UTF-8");
$html->preserveWhiteSpace = false;
$html->formatOutput = false;
$html->loadHTMLFile($this->model->document, \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);

$xpath = new \DOMXPath($html);
foreach ($xpath->query("//*") as $node) {
    $model = new \Modules\Node\Html\Model;
    $model->store($this->model->restore());
    $model->node = $node;
    $model->use = "\Modules\Node\Html";
    $model->setup();
}   