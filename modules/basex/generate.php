<?php
if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $model = new \Modules\BaseX\Model;
    $model->store($this->request->restore());
    $model->store($this->model->restore());
    $model->setup();
}