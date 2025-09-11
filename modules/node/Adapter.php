<?php

namespace Modules\Node {

    abstract class Adapter extends \Component\Core\Adapter\Models {

        public function models(array $models = [], string $query = "//*"): array {
            foreach ($this->query($query) as $node) {
                $model = new $this->model;
                $model->store($this->restore($this->diff(["model"])));
                $model->node = $node;
                $model->setup();
                
                $models[(string) $model] = $model;
            }

            return (array) $models;
        }
    }

}
