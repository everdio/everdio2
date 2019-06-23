<?php



$mongo = new \Components\Resource\MongoDB;
$db = $mongodb->test;
print_r($db);
die();








$basex = new \Modules\Basex("//items/item[1]/parent::items");
$basex->store($this->basex);



$xml = simplexml_load_string($basex->execute(), "SimpleXMLElement",  LIBXML_NOCDATA | LIBXML_NONET | LIBXML_NOWARNING);
$json = json_encode($xml);
$array = json_decode($json,TRUE);




