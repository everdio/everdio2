<?php
namespace Component {
    trait Helpers {
        public function slug($string, $replace = "-"){
            return (string) trim(preg_replace('/\W+/', $replace, trim(strtolower($string), $replace)), $replace);
        }
        
        public function formatsize($size, $precision = 2, $suffixes = ['B', 'kB', 'MB', 'GB']) {
            $base = log(floatval($size)) / log(1024);
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }        
        
        public function labelize($name) : string {
            return (string) preg_replace("/[^A-Za-z]/", false, implode("", array_map("ucFirst", explode("_", str_replace("/", "_", str_replace("-", "_", str_replace(" " , "_", strtolower($name))))))));
        }        
        
        public function substring($string, int $start = 0, $length = 25, string $prefix = NULL, string $suffix = NULL, $encoding = "UTF-8") : string {
            return (string) (strlen($string) >= $length ? $prefix . mb_substr($string, $start, $length, $encoding) . $suffix : $string);
        }    

        public function sanitize($data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->sanitize($value);
                }
            } elseif (is_string($data)) {
                return (string) htmlspecialchars(addslashes((string) $data));
            }
            
            return $data;
        }         
        
        public function desanitize($data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->desanitize($value);
                }
            } elseif (is_string($data)) {
                return (string) html_entity_decode((string) $data);
            }
            
            return $data;
        }
        
        public function str_limit(array $values, int $minimum = 30, int $total = 9999, array $return = [], int $count = 0) : array {
            foreach ($values as $value) {
                if (is_string($value)) {
                    
                    $length = strlen(trim($value));
                    if ($length >= $minimum && ($length + $count) <= $total) {
                        $return[] = trim($value);
                        $count += $length;
                    }
                }
            }
            
            return (array) array_unique($return);
        } 

        public function phrases(string $string, int $minimum = 40, int $total = 9999, $implode = ". ", array $phrases = [], int $count = 0) : string {
            foreach (explode($implode, strip_tags($this->desanitize(($string)))) as $phrase) {
                if (strlen($phrase) >= $minimum && (strlen($phrase) + $count) <= $total) {
                    $phrases[] = trim($phrase);
                    $count += strlen($phrase);
                }
            }
            
            return (string) implode($implode, $phrases);
        }
        
        
        public function words(string $string, int $minimum = 6, $total = 9999, array $words = [], int $count = 0) : array {
            foreach (array_reverse((array) str_word_count(strip_tags($string), 2)) as $word) {
                if (strlen($word) >= $minimum && (strlen($word) + $count) <= $total) {
                    $words[] = $word;
                    $count += strlen($word);
                }            
            }
            return (array) array_unique($words);
        }        
    }
}

