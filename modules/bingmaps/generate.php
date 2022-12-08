<?php

foreach ($this->openweather->api->restore() as $api => $url) {
    $model = new \Modules\OpenWeather\Model;
    $model->store($this->model->restore());
    $model->store($this->openweather->restore());
    $model->class = $this->getLabelized($api);
    $model->url = $url;
    $model->setup();
    
    echo sprintf("%s\%s", $model->namespace, $model->class) . PHP_EOL;
    ob_flush();    
}



