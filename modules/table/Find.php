<?php

namespace Modules\Table {

    final class Find extends \Component\Validation {

        public function __construct(array $validations = [], string $operator = "AND", array $selects = [], ?string $from = null, array $joins = [], array $operators = [], ?string $orderby = null, ?string $groupby = null, ?string $limit = null) {
            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {
                    if ($validation instanceof Select || $validation instanceof Count) {
                        $selects = \array_merge($selects, $validation->execute());
                    } elseif ($validation instanceof From) {
                        $from = $validation->execute();
                    } elseif ($validation instanceof Filter || $validation instanceof In) {
                        $operators[] = $validation->execute();
                    } elseif ($validation instanceof Joins) {
                        $joins[] = $validation->execute();
                    } elseif ($validation instanceof GroupBy) {
                        $groupby = $validation->execute();
                    } elseif ($validation instanceof OrderBy) {
                        $orderby = $validation->execute();
                    } elseif ($validation instanceof Limit) {
                        $limit = $validation->execute();
                    }
                }
            }
            parent::__construct(\trim(\sprintf("SELECT %s", \implode(" ", \array_filter([\implode(", ", $selects), $from, \implode(" ", \array_reverse($joins)), (new Operators($operators, $operator))->execute(), $groupby, $orderby, $limit])))), [new \Component\Validator\IsString\Contains(["FROM"])]);
        }
    }

}

