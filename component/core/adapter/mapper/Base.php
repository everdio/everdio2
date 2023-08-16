<?php

namespace Component\Core\Adapter\Mapper {

    interface Base {

        public function count(array $validations = [], string $query = NULL): int;

        public function find(array $validations = [], string $query = NULL): self;

        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = NULL): array;

        public function connect(\Component\Core\Adapter\Mapper $mapper): self;

        public function save(): self;

        public function delete(): self;
    }

}