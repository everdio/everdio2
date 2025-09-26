<?php

namespace Modules\Table\Adapter {

    use Component\Validation,
        Component\Validator;

    final class Sqlite extends \Modules\Table\Adapter {

        use \Modules\Table\Sqlite;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "database" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }

        final public function models(array $models = []): array {
            $path = \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)));
            $dsn = \sprintf("sqlite:%s/%s.db", (new \Component\Path(\dirname($path)))->getPath(), \basename($path));

            /*
            foreach ($this->database as $mapper) {
                $parameters = [];

                if (isset($this->{$mapper}) && isset($this->{$mapper}->mapping)) {
                    foreach ($this->{$mapper}->mapping->restore() as $parameter) {
                        if (isset($this->{$mapper}->{$parameter})) {
                            $parameters[$parameter] = (new \Component\Validation\Parameter($this->{$mapper}->{$parameter}->value, $this->{$mapper}->{$parameter}->default, $this->{$mapper}->{$parameter}->mandatory, $this->{$mapper}->{$parameter}->length, \explode(",", $this->{$mapper}->{$parameter}->options)))->getValidation();
                        }
                    }

                    $model = new \Modules\Table\Model\SQLite($parameters);
                    $model->dsn = $dsn;
                    $model->namespace = $this->namespace;
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

                    $models[] = $model;
                }                
            }
             * 
             */
            return (array) $models;            
        }        
    }

}