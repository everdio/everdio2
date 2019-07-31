<?php
namespace Components\Core\Mapping {
    interface Mapper {
        public function find(array $operators = [], string $query = NULL);
        
        public function findAll(array $operators = [], string $query = NULL) : array;
        
        public function save();
        
        public function delete();
        
        static public function construct(array $values = []) : \Components\Core\Mapping;
    }
}

