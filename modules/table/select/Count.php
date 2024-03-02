<?php

namespace Modules\Table\Select {

    class Count extends \Modules\Table\Select {

        public function __construct(string $count = "*") {
            parent::__construct([\sprintf(" COUNT(DISTINCT %s) ", $count)]);
        }
    }

}