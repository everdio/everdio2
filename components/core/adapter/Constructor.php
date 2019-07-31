<?php
namespace Components\Core\Adapter {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Constructor extends \Components\Core\Adapter {
        public function __construct(string $construct) {
            $this->add("construct", new Validation($construct, [new Validator\IsString]));
            $this->add("instance", new Validation($this->initialize(), [new Validator\IsObject]));
        }
        
        protected function initialize() {
            $reflector = new \ReflectionClass($this->construct);
            return (object) $reflector->newInstanceArgs($this->restore($this->diff(["construct"])));
        }
    }
}

