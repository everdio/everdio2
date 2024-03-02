<?php

namespace Modules\Table {

    use \Component\Validator;

    class Select extends \Component\Validation {

        public function __construct(array $select) {
            parent::__construct(\sprintf("SELECT%s", \implode(",", $select)), [new Validator\IsString]);
        }
    }

}