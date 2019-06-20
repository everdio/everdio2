<?php
namespace Components {
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
            return (string) (strlen($string) > $length ? $prefix . mb_substr($string, $start, $length, $encoding) . $suffix : $string);
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
        
        public function phrases(string $string, int $length = 40, array $phrases = []) : array {
            foreach (explode(". ", trim(strip_tags($this->desanitize(($string))))) as $phrase) {
                if (strlen($phrase) >= $length) {
                    $phrases[] = trim($phrase);
                }
            }
            
            return (array) $phrases;
        }
        
        
        public function words(string $string, int $length = 6, int $return = 100) : array {
            $words = (array) str_word_count(strip_tags($string), 2);
            foreach ($words as $count => $word) {
                if (strlen($word) <= $length) {
                    unset ($words[$count]);
                }            
            }

            return (array) array_slice(array_unique($words), 0, $return);
        }        
    }
}

