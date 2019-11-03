<?php
namespace Components\Core\Caller {
    use \Components\Validation;
    use \Components\Validator;     
    class MysqlI extends \Components\Core\Caller {
        public function __construct(array $options = []) {
            parent::__construct("mysqli");
            $this->add("options", new Validation($options, [new Validator\IsArray]));
        }
        
    
        public function __dry() : string {
            return (string) sprintf("new %s(%s)", get_class($this), $this->dehydrate($this->options));
        }
    }
}