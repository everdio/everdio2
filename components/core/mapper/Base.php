<?php
namespace Components\Core\Mapper {
    interface Base {                
        public function find(array $validations = [], string $query = NULL);
        
        public function findAll(array $validations = [], string $query = NULL) : array;
        
        public function save();
        
        public function delete();
    }
}