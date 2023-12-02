<?php

if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $this->echo(\sprintf("Generating %s .. ", $this->model->namespace), ["cyan"]);

    $model = new \Modules\BaseX\Model;
    $model->model = __DIR__ . \DIRECTORY_SEPARATOR . "Model.tpl";
    $model->store($this->basex->restore());
    $model->store($this->model->restore());
    $model->setup();
    $this->echo("done", ["green"]);
    $this->break(2);
}