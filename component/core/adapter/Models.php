<?php

namespace Component\Core\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Models extends \Component\Core\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray]),
                "namespace" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }

        abstract protected function models(array $models = []): array;

        public function deploy(array $models = []): array {
            foreach ($this->models($models) as $key => $model) {
                if ($model instanceof Model) {
                    $model->deploy();
                    $models[$key] = (string) $model;
                }
            }

            return (array) $models;
        }
    }

}