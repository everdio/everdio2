<?php
if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $model = new \Modules\BaseX\Model\Memcached;
    $model->store($this->request->restore());
    $model->store($this->model->restore());
    $model->memcached->store($this->memcached->restore());
    $model->setup();
}