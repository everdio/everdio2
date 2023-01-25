<?php

if ($this instanceof Component\Core\Controller\Model\Cli) {
    $this->echo(\sprintf("Generating %s .. ", $this->model->document), ["cyan"], false);
    
    $dom = new \DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    $dom->recover = true;
    $dom->substituteEntities = false;
    $dom->loadHTMLFile($this->model->document, \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);

    $xpath = new \DOMXPath($dom);
    foreach ($xpath->query("//*") as $node) {
        $model = new \Modules\Node\Html\Model;
        $model->store($this->model->restore());
        $model->node = $node;
        $model->use = "\Modules\Node\Html";
        $model->setup();
    }   
    
    $this->echo("done", ["green"], \PHP_EOL . \PHP_EOL);
}