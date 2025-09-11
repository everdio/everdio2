<?php
if ($this instanceof Application\Command) {
    $path = \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->model->namespace)));
    
    $this->model->dsn = \sprintf("sqlite:%s/%s.db", (new \Component\Path(\dirname($path)))->getPath(), \basename($path));

    $models = [];
    
    foreach ($this->model->tables->restore() as $mapper) {
        $parameters = [];

        if (isset($this->{$mapper}) && isset($this->{$mapper}->mapping)) {
            foreach ($this->{$mapper}->mapping->restore() as $column => $parameter) {
                if (isset($this->{$mapper}->{$parameter})) {
                    $parameters[$parameter] = (new \Component\Validation\Parameter($this->{$mapper}->{$parameter}->value, $this->{$mapper}->{$parameter}->default, $this->{$mapper}->{$parameter}->mandatory, $this->{$mapper}->{$parameter}->length, \explode(",", $this->{$mapper}->{$parameter}->options)))->getValidation();
                }
            }
            
            $model = new \Modules\Table\Model\SQLite($parameters);
            $model->store($this->model->restore());
            $model->table = $this->{$mapper}->table;
            
            if (isset($this->{$mapper}->primary)) {
                $model->primary = $this->{$mapper}->primary->restore();
            }
            
            if (isset($this->{$mapper}->keys)) {
                $model->keys = $this->{$mapper}->keys->restore();
            }
            
            $model->mapping = $this->{$mapper}->mapping->restore();
            
            if (isset($this->{$mapper}->parents)) {
                foreach ($this->{$mapper}->parents->restore() as $key => $parent) {
                    if (\array_key_exists($parent, $models)) {
                        $model->parents = [$key => $models[$parent]];
                    }
                }
            }
            
            $model->setup();
            $model->deploy();
            
            $models[$mapper] = (string) $model;
            
            $this->echo($this->style((string) $model . \PHP_EOL, ["white", "bold"]));
        }
    }

    $this->break();
}