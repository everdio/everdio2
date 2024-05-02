<?php

namespace Modules\Node {

    final class Find extends \Component\Validation {

        public function __construct(string $xpath, array $validations = [], string $wrap = "(%s)") {
            $xparts = $parts = \explode(\DIRECTORY_SEPARATOR, $this->clean($xpath));

            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {
                    if (\array_key_exists(($end = \array_key_last(\array_intersect(($fparts = \explode(\DIRECTORY_SEPARATOR, ($fpath = $this->clean(($filter = $validation->execute()))))), $parts))), $xparts)) {
                        if (($filter = \str_replace($fpath, false, $filter))) {
                            $xparts[$end] .= (\sizeof(\array_diff($fparts, $parts)) ? "[" . \implode(\DIRECTORY_SEPARATOR, \array_diff($fparts, $parts)) . $filter . "]" : $filter);
                        } elseif (\sizeof(\array_diff($fparts, $parts))) {
                            $xparts[$end] .= "[" . \implode(\DIRECTORY_SEPARATOR, \array_diff($fparts, $parts)) . "]";
                        }
                    }
                }
            }

            parent::__construct(\sprintf($wrap, \implode(\DIRECTORY_SEPARATOR, $xparts)), [new \Component\Validator\IsString]);
        }

        public function clean(string $xpath): string {
            return (string) \preg_replace("/\[(.*?)\]/", false, $xpath);
        }
    }

}