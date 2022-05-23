<?php

foreach ($this->openweather["api"] as $api => $url) {
    $model = new \Modules\OpenWeather\Model;
    $model->store($this->model);
    $model->store($this->openweather);
    $model->class = $this->labelize($api);
    $model->url = $url;
    $model->setup();
    
    echo sprintf("%s\%s", $model->namespace, $model->class) . PHP_EOL;
    ob_flush();    
}



