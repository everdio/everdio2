<?php

namespace Modules\Node {

    abstract class Adapter extends \Component\Core\Adapter\Base {

        public function setup(array $parameters = [], array $models = [], string $query = "//*"): array {
            foreach ($this->query($query) as $node) {
                $model = new $this->model;
                $model->store($parameters);
                $model->node = $node;
                $model->setup();

                $models[] = $model;
            }

            return (array) $models;
        }
    }

}
