<?php
namespace Components\Core\Adapter\Mapper {
    interface Base {
        public function find(array $operators = [], string $query = NULL);
        
        public function findAll(array $operators = [], string $query = NULL, array $records = []) : array;
        
        public function save();
        
        public function delete();
    }
}