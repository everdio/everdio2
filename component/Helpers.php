<?php
namespace Component {
    trait Helpers {
        public function slug($string, $replace = "-"){
            return (string) \trim(\preg_replace('/\W+/', $replace, \trim(\strtolower(\Transliterator::createFromRules(':: Any-Latin;' . ':: NFD;' . ':: [:Nonspacing Mark:] Remove;' . ':: NFC;' . ':: [:Punctuation:] Remove;' . ':: Lower();' . '[:Separator:] > \'-\'')->transliterate($string)), $replace)), $replace);
        }
        
        public function formatsize($size, $precision = 2, $suffixes = ['B', 'kB', 'MB', 'GB']) {
            $base = \log(\floatval($size)) / \log(1024);
            return \round(\pow(1024, $base - \floor($base)), $precision) . $suffixes[\floor($base)];
        }        
        
        public function labelize(string $name) : string {
            return (string) \preg_replace("/[^A-Za-z]/", false, \implode("", \array_map("ucFirst", \explode("_", \str_replace("/", "_", \str_replace("-", "_", \str_replace(" " , "_", \strtolower($name))))))));
        }        
        
        public function substring($string, int $start = 0, $length = 25, string $prefix = NULL, string $suffix = NULL, bool $fill = false, $encoding = "UTF-8") : string {
            return (string) (\strlen($string) >= $length ? $prefix . \mb_substr($string, $start, $length, $encoding) . $suffix : ($fill ? \str_pad($string, $length + \strlen($suffix), " ", \STR_PAD_RIGHT) : $string));
        }    

        public function sanitize($data) {
            if (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->sanitize($value);
                }
            } elseif (\is_string($data)) {
                return (string) \htmlspecialchars(\addslashes($data));
            }
            
            return $data;
        }         
        
        public function desanitize($data) {
            if (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->desanitize($value);
                }
            } elseif (\is_string($data)) {
                return (string) \rawurldecode(\htmlspecialchars_decode(\str_replace("\\\\", "\\", $data)));
            }
            
            return $data;
        }

        public function phrases(string $content, int $min = 40, int $total = 9999, array $sentences = [], int $count = 0) : array {
            foreach ((array) \preg_split('/(?<=[.?!])\s+(?=[a-z])/i', \strip_tags($content)) as $sentence) {
                if (\strlen($sentence) >= $min && (\strlen($sentence) + $count) <= $total && \sizeof($this->words($sentence, 2)) > 1) {
                    $sentences[] = \trim($sentence);
                    $count += \strlen($sentence);
                }                
            }
            
            return (array) $sentences;
        }
        
        
        public function words(string $content, int $min = 6, $max = 9999, array $exclude = [], array $words = [], int $count = 0) : array {
            $list = \array_count_values(\str_word_count(\strtolower(\strip_tags($content)), 1));            
            \asort($list);
            foreach (\array_keys(\array_reverse($list)) as $word) {
                if (\strlen($word) >= $min && (\strlen($word) + $count) <= $max) {
                    $words[] = trim($word);
                    $count += \strlen($word);
                }            
            }
            
            return (array) $words;
        }        
    }
}

