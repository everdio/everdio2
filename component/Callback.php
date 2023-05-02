<?php
namespace Component {
    trait Callback {
        public function callback(string $url, array $arguments = []) {
            $function = \parse_url(\html_entity_decode($url), \PHP_URL_HOST);
            
            if (($query = \parse_url(\html_entity_decode($url), \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }   
            
            //if (($method = \parse_url($url, \PHP_URL_SCHEME))) {                                        
            if (($method = \strstr($url, ":", true))) {           
                try {                    
                    return ($function ? \call_user_func($function, \call_user_func_array([$this, $method], \array_values($arguments))) : \call_user_func_array([$this, $method], \array_values($arguments)));
                } catch (\TypeError $ex) {
                    throw new \BadMethodCallException($ex->getMessage(), 0, $ex);
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException($ex->getMessage(), 0, $ex);
                }                              
            } elseif ($function) {
                try {
                    return \call_user_func_array($function, \array_values($arguments));
                } catch (\TypeError $ex) {                            
                    throw new \BadFunctionCallException($ex->getMessage(), 0, $ex);
                }
            }         
        }                         
    }
}