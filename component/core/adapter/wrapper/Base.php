<?php
namespace Component\Core\Adapter\Wrapper {
    interface Base {
        public function find();
        
        public function save($data);
    }
}