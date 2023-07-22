<?php
if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $this->echo(\sprintf("Generating %s .. ", $this->model->document), ["cyan"]);

    $dom = new \DOMDocument("1.0", "UTF-8");
    $dom->load($this->model->document, \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_NOBLANKS);    

    $xpath = new \DOMXPath($dom);
    foreach ($xpath->query("//*") as $node) {
        $model = new \Modules\Node\Xml\Model;
        $model->store($this->model->restore());
        $model->node = $node;
        $model->use = "\Modules\Node\Xml";        
        $model->setup();    
    }

    $this->echo("done", ["green"]);
    $this->break(2);
}