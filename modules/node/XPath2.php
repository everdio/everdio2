<?php
namespace Modules\Node {
    use \Components\Validator;
    class XPath2 extends \Components\Validation {
        public function __construct($path, array $operators = [], string $xpath = NULL) {
            foreach (explode(DIRECTORY_SEPARATOR, $path) as $key => $part) {
                $cpath[$key] = $part;
                $xpath[$key] = $part;
                foreach ($operators as $operator) {
                    if ($operator instanceof XOperator && implode(DIRECTORY_SEPARATOR, $cpath) === $operator->mapper->path && ($operator->mapper->hasMapping() || isset($operator->mapper->Text))) {
                        $xpath[$key] = $part . $operator->execute();
                    }
                }
            }
            
            parent::__construct(implode(DIRECTORY_SEPARATOR, $xpath), [new Validator\IsString\IsPath]);
        }
    }
}