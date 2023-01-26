<?php
$model = new \Modules\IpApi\Model;
$model->store($this->ipapi->restore());
$model->store($this->model->restore());
$model->setup();



