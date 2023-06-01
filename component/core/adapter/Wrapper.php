<?php
namespace Component\Core\Adapter {
    use \Component\Validation, \Component\Validator;    
    class Wrapper extends \Component\Core\Adapter {
        public function __construct(string | int $key, object $object) {
            parent::__construct([
                "key" => new Validation($key, [new Validator\IsString, new Validator\IsInteger]),
                "adapter" => new Validation(["key"], [new Validator\IsArray]),
                "object" => new Validation($object, [new Validator\IsObject])
            ]);
        }

        final protected function __init() : object {       
            return (object) $this->object;
        }    
        
        final public function __dry() : string {
            return (string) \sprintf("new \%s(%s, %s)", (string) $this, $this->key, $this->dehydrate($this->object));
        }           
    }
}