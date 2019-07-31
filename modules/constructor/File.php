<?php
namespace Modules\Constructor {
    use \Components\Validation;
    use \Components\Validator;    
    class File extends \Components\Core\Adapter\Constructor {
        public function __construct(string $construct, string $file, int $options = NULL) {
            $this->add("file", new Validation($file, [new Validator\IsString\IsFile]));
            $this->add("options", new Validation($options, [new Validator\IsInteger]));
            parent::__construct($construct);
        }
        
        public function __dry() : string {
            return (string) sprintf("%s(\"%s\", \"%s\")", $this->construct, $this->file, $this->options);
        }
    }
}

