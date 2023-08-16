<?php

namespace Component\Core\Adapter\Wrapper {

    interface Base {

        public function find(): self;

        public function save(): self;
    }

}