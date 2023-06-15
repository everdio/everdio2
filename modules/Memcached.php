<?php
namespace Modules {
    use \Component\Validation, \Component\Validator;
    class Memcached extends \Component\Core\Adapter\Wrapper {
        final public function __construct() {
            parent::__construct([
                "key" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "ttl" => new Validation(false, [new Validator\IsInteger]),
            ]);
        }
        
        final public function find() {
            if (($response = $this->get($this->key)) && $this->getResultCode === 0) {
                return $response;
            }
        }
        
        final public function save($data) {
            $this->add($this->key, $data, $this->ttl);
        }
        
        final protected function __init() : object {       
            return (object) new \Memcached($this->id);
        }                
        
        final public function __dry() : string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->key));
        }
    }
}