<?php

namespace Modules\Node {

    final class Not extends \Component\Validation {

        public function __construct(\Component\Validation $validation) {
            parent::__construct(\sprintf("not(.%s)", $validation->execute()), [new \Component\Validator\IsString]);
        }
    }

}