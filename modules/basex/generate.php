<?php

if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $this->echo(\sprintf("Generating %s .. ", $this->model->namespace), ["cyan"]);

    $model = new \Modules\BaseX\Model;
    $model->store($this->basex->restore());
    $model->store($this->model->restore());
    $model->setup();

    $this->echo("done", ["green"]);
    $this->break(2);
}