<?php
namespace Components\Core\Controller {
    use \Components\Validation, \Components\Validator;
    abstract class Model extends \Components\Core\Controller {    
        public function __construct(array $parameters = [], \Components\Parser $parser) {
            parent::__construct([
                "parser" => new Validation($parser, array(new Validator\IsObject\Of("\Components\Parser")))
            ] + $parameters);
        }
        
        final protected function dispatch(string $path) {   
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . $this->parser::EXTENSION, array(new Validator\IsString\IsFile));
            if ($validation->isValid()) {
                $file = new \Components\File($validation->execute(), "r");                   
                foreach (array_merge_recursive($this->parser::parse($file->restore())) as $parameter => $value) {    
                    $parameter = new \Components\Validation\Parameter($parameter, $value, true);                    
                    $this->add((string) $parameter, $parameter->getValidation($parameter->getValidators()));
                }                         
                
            }
            return (string) parent::dispatch($path);
        }       
    }
}


