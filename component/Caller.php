<?php
namespace Component {
    abstract class Caller {
        use Helpers, Dryer;
        private $_call;
        protected $resource;

        public function __construct($_call) {
            $this->_call = $_call;
        }
        
        final public function getResource() {
            return $this->resource;
        }
        
        final public function __toString() : string {
            return (string) $this->_call;
        }

        final public function __call($name, $arguments) {
            if (\function_exists($this->_call . $name)) {
                if (isset($this->resource)) {
                    \array_unshift($arguments, $this->resource); 
                }
                
                return \call_user_func_array($this->_call . $name, $arguments);            
            }
            
            throw new \BadFunctionCallException(sprintf("unknown function call %s (%s)", $this->_call . $name, $this->dehydrate($arguments)));
        }       
        
        public function __dry() : string {
            return (string) \sprintf("new %s(%s, \"w+\")", (string) $this, $this->_call);
        }        
    }
}

