<?php
namespace Component\Core\Adapter {
    use \Component\Validation, \Component\Validator;    
    class Wrapper extends \Component\Core\Adapter {
        public function __construct(string | int $id, object $object) {
            parent::__construct([
                "id" => new Validation($id, [new Validator\IsString, new Validator\IsInteger]),
                "adapter" => new Validation(["id"], [new Validator\IsArray]),
                "object" => new Validation($object, [new Validator\IsObject])
            ]);
        }

        final protected function __init() : object {       
            return (object) $this->object;
        }    
    }
}