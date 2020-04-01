<?php
namespace Components\Core\Mapper {
    interface Base {                
        public function find(array $filters = [], string $query = NULL);
        
        public function findAll(array $filters = [], string $query = NULL) : array;
        
        public function save();
        
        public function delete();
    }
}