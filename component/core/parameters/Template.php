<?php
namespace Component\Core\Parameters {
    class Template extends \Component\Core\Parameters {
        public function import(string $querystring, array $cores = []) {
            parse_str($querystring, $cores);
            foreach ($cores as $core => $values) {
                foreach ($values as $parameter => $value) {
                    $this->{sprintf("%s[%s]", $core, $parameter)} = $value;
                }
            }
        }

    }
}
