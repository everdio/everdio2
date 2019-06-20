<?php
namespace Components\Core\Mapper {
    interface Base {
        public function find(array $thatMappers = [], string $query = NULL);
        
        public function findAll(array $thatMappers = [], string $query = NULL) : array;
        
        public function save();
        
        public function delete();
        
        static public function construct(array $values = []) : \Components\Core\Mapper;
    }
}

