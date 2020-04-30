<?php
namespace Components\Core\Controller {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Model extends \Components\Core\Controller {    
        public function __construct(\Components\Parser $parser) {
            parent::__construct([
                "parser" => new Validation($parser, array(new Validator\IsObject\Of("\Components\Parser")))
            ]);
        }
        
        final protected function dispatch(string $path) {   
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . $this->parser::EXTENSION, array(new Validator\IsString\IsFile));
            if ($validation->isValid()) {
                $file = new \Components\File($validation->execute(), "r");                   
                foreach ($this->parser::parse($file->restore()) as $parameter => $value) {
                    $this->add($parameter, new \Components\Validation($value, array(new Validator\IsString, new Validator\IsArray, new Validator\IsNumeric)), true);
                }                         
            }
            
            return (string) parent::dispatch($path);
        }       
    }
}


