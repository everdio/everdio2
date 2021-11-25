<?php
namespace Component\Core\Adapter\Mapper {
    trait Duplication {
        final public function duplication(\Component\Core\Adapter\Mapper $mapper, array $values = [], array $parameters = []) : \Component\Core\Adapter\Mapper {
            $mapper = (isset($mapper->mapping) ? $mapper->duplicate(new $mapper, \array_values($mapper->mapping)): $mapper->duplicate(new $mapper));
            $mapper->store($values);
            return (object) $mapper;
        }

        public function __call(string $method, array $arguments = []) {
            if (isset($this->library->{$method})) {        
                return \call_user_func_array(array($this, "duplication"), \array_merge([$this->library->{$method}], $arguments));
            }

            return parent::__call($method, $arguments);
        }          
    }
}
