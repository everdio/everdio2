<?php
namespace Components {
    trait Dryer {        
        public function dehydrate($data, array $output = []) : string {
            if (is_numeric($data) || is_integer($data)) {
                return (string) $data;
            } elseif (is_string($data)) {
                return (string) sprintf("\"%s\"", $data);    
            } elseif (is_array($data)) {
                foreach ($data as $key => $value) {
                    $output[] = (is_integer($key) ? false : "\"" . $key . "\" => ") . ($this->dehydrate($value));
                }
                return (string) sprintf("[%s]", implode(", ", $output));                
            } elseif (is_object($data)) {
                if (method_exists($data, __FUNCTION__)) {
                    return (string) $data->__dry();
                } else {
                    $reflection = new \ReflectionClass($data);
                    if ($reflection->isInstantiable()) {
                        $output = [];
                        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
                            foreach ($reflection->getProperties() as $property) {
                                if ($property->name === $parameter->name) {
                                    $property->setAccessible(true);                        
                                    $output[] = $this->dehydrate($property->getValue($data));
                                }
                            }
                        }       
                        return (string) sprintf("new \%s(%s)", $reflection->getName(), implode(", ", $output));            
                    } else {
                        return (string) "false";
                    }
                }
            } elseif (is_bool($data) || is_double($data)) {
                return (string) ($data === true ? "true" : "false");
            } elseif ($data === NULL) {
                return (string) "NULL";
            } elseif (is_resource($data)) {
                return (string) get_resource_type($data);
            } else {
                throw new Event(sprintf("%s %s", $data, gettype($data)));
            }
        }

        public function hydrate($data) {
            if (is_numeric($data)) {
                return (int) $data;
            } elseif (is_bool($data)) {
                return (bool) $data;
            } elseif (is_string($data)) {
                return (string) $data;
            } elseif (is_array($data)) {
                return (array) $data;
            } else {
                return $data;
            }
        }
        
        abstract public function __dry() : string;  
    }
}

