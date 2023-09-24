<?php

if ($this instanceof \Component\Core\Controller\Model\Cli) {
    $this->echo(\sprintf("Generating %s .. ", $this->model->namespace), ["cyan"]);
    foreach ($this->api->restore() as $api => $url) {
        $model = new \Modules\OpenWeather\Model;
        $model->store($this->model->restore());
        $model->store($this->openweather->restore());
        $model->class = $this->getLabelized($api);        
        $model->url = $url;
        
        $model->setup();
    }

    $this->echo("done", ["green"]);
    $this->break(2);
}



