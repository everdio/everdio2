<?php
$model = new \Modules\BaseX\Model;
$model->store($this->basex);
$model->store($this->model);
$model->setup();