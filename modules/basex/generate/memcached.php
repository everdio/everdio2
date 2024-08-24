<?php

if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $this->echo($this->style(\sprintf("Generating %s .. ", $this->model->namespace), ["cyan"]));

    $model = new \Modules\BaseX\Model\Memcached;
    $model->store($this->request->restore());
    $model->store($this->model->restore());
    $model->memcached->store($this->memcached->restore());
    $model->setup();

    $this->echo($this->style("done", ["green"]));
    $this->break(1);
}