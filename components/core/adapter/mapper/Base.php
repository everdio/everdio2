<?php
namespace Components\Core\Adapter\Mapper {
    interface Base {                
        
        public function count(array $validations = [], string $query = NULL) : int;
        
        public function find(array $validations = [], string $query = NULL) : \Components\Core\Adapter\Mapper;
        
        public function findAll(array $validations = [], int $position = 0, int $limit = 0, string $query = NULL) : array;
        
        public function save() : \Components\Core\Adapter\Mapper;
        
        public function delete();
    }
}