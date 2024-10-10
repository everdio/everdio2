<?php

namespace Modules\Node {

    use Component\Validation,
        Component\Validator;

    abstract class Adapter extends \Component\Core\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);
        }

        public function generate(array $parameters = [], string $query = "//*"): void {
            foreach ($this->query($query) as $node) {
                $model = new $this->model;
                $model->store($parameters);
                $model->node = $node;
                $model->setup();
            }
        }
    }

}
