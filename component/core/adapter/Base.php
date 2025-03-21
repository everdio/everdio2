<?php

namespace Component\Core\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Base extends \Component\Core\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);
        }
        
        abstract public function setup(array $parameters = [], array $models = []): array;
        
        public function deploy(array $parameters = []): void {
            foreach ($this->setup($parameters) as $model) {
                $model->deploy();
            }
        }        
    }

}