<?php
namespace Modules\Node {
    use \Components\Validator;
    final class XPath extends \Components\Validation {
        public function __construct($path, array $operators = [], array $xpath = NULL) {
            foreach (explode(DIRECTORY_SEPARATOR, $path) as $key => $part) {
                $cpath[$key] = $part;
                $xpath[$key] = $part;
                foreach ($operators as $operator) {
                    if ($operator instanceof XOperator && implode(DIRECTORY_SEPARATOR, $cpath) === $operator->path && !in_array(false, $operator->validate())) {
                        $xpath[$key] = $part . $operator->execute();
                    }
                }
            }
            
            parent::__construct(implode(DIRECTORY_SEPARATOR, $xpath), [new Validator\IsString\IsPath]);
        }
    }
}