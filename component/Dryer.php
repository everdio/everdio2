<?php
namespace Component {
    trait Dryer {        
        public function dehydrate($data, array $array = []) : string {
            if (is_numeric($data) || is_integer($data)) {
                return (string) $data;            
            } elseif (is_bool($data)) {
                return (string) ($data === true ? "true" : "false");            
            } elseif (is_string($data)) {
                return (string) sprintf("\"%s\"", $data);
            } elseif (is_array($data)) {
                foreach ($data as $key => $value) {
                    $array[] = (is_integer($key) ? false : $this->dehydrate($key) . " => ") . ($this->dehydrate($value));
                }
                return (string) sprintf("[%s]", implode(sprintf(","), $array));                
            } elseif (is_object($data)) {
                if (method_exists($data, __FUNCTION__)) {
                    return (string) $data->__dry();
                } else {
                    $reflection = new \ReflectionClass($data);
                    if ($reflection->isInstantiable() && $reflection->hasMethod("__construct")) {
                        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
                            foreach ($reflection->getProperties() as $property) {
                                if ($property->name === $parameter->name) {
                                    $property->setAccessible(true);                        
                                    $array[] = $this->dehydrate($property->getValue($data));
                                }
                            }
                        }       
                        return (string) sprintf("new \%s(%s)", $reflection->getName(), implode(", ", $array));            
                    } else {
                        return (string) "false";
                    }
                }
            } elseif ($data === NULL) {
                return (string) "false";
            } elseif (is_resource($data)) {
                return (string) get_resource_type($data);
            } else {
                throw new \RuntimeException (sprintf("%s %s", $data, gettype($data)));
            }
        }

        public function hydrate($data) {
            if (is_numeric($data) || is_integer($data)) {
                if (floatval($data) != intval($data)) {
                    return (float) $data;
                } else {
                    return (int) $data;
                }
            } elseif (is_bool($data)) {
                return (bool) $data;
            } elseif (is_string($data)) {
                return (string) $data;
            } elseif (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->hydrate($value);
                }
                return (array) $data;
            } else {
                return $data;
            }
        }
        
        abstract public function __dry() : string;  
    }
}

