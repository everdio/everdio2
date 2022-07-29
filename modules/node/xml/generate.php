<?php
$simplexml = \simplexml_load_file($this->model->document, NULL, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT);
foreach ($simplexml->xpath("//*") as $index => $node) {
    $model = new \Modules\Node\Xml\Model;
    $model->store($this->model->restore());
    $model->node = \dom_import_simplexml($node);
    $model->use = "\Modules\Node\Xml";        
    $model->setup();           
}