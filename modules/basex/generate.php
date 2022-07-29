<?php

$model = new \Modules\BaseX\Model;
$model->store($this->basex->restore());
$model->store($this->model->restore());
$model->setup();