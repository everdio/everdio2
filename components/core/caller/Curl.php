<?php
namespace Components\Core\Caller {
    use \Components\Validation;
    use \Components\Validator;     
    class Curl extends \Components\Core\Caller {
        public function __construct(array $options = []) {
            parent::__construct("curl");
            $this->add("options", new Validation($options, [new Validator\IsArray]));
            $this->resource = $this->init();
        }
        
        public function execute() {
            $this->setopt_array($this->options);
            if (($response = $this->exec()) === false) {                
                throw new Event($this->error());
            }
            
            return (string) trim($response);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", get_class($this), $this->dehydrate($this->options));
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}