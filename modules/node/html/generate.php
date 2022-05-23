<?php
\libxml_use_internal_errors(false);

$html = new \DOMDocument("1.0", "UTF-8");
$html->preserveWhiteSpace = false;
$html->formatOutput = false;
$html->loadHTMLFile($this->model["document"], \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING);

$xpath = new \DOMXPath($html);
foreach ($xpath->query("//*") as $node) {
    $model = new \Modules\Node\Html\Model;
    $model->store($this->model);
    $model->node = $node;
    $model->use = "\Modules\Node\Html";
    $model->setup();
}   