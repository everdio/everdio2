<?php
namespace Component\Core\Adapter\Mapper {
    interface Base {                
        public function fetch(string $query);
        
        public function count(array $validations = [], string $query = NULL) : int;
        
        public function find(array $validations = [], string $query = NULL) : \Component\Core\Adapter\Mapper;
        
        public function findAll(array $validations = [], int $position = 0, int $limit = 0, string $query = NULL) : array;
        
        public function save() : \Component\Core\Adapter\Mapper;
        
        public function delete() : void;
    }
}