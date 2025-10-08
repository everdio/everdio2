<?php

namespace Component {

    trait Helpers {

        public function reName($string, string $replace = " "): string {
            return (string) \Transliterator::createFromRules(\sprintf(":: Any-Latin; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC; :: [:Punctuation:] [:Separator:] > '%s'", $replace))->transliterate(\strip_tags(\nl2br(\html_entity_decode(\trim(\str_replace(["\""], false, $string)), \ENT_QUOTES | \ENT_HTML5))));
        }

        public function slugify($string, string $replace = "-"): string {
            return (string) \trim(\preg_replace("/\W+/", $replace, \trim(\strtolower(\str_replace("_", $replace, $this->reName($string, $replace))), $replace)), $replace);
        }

        public function beautify(string $string, string $replace = "-" ): string {
            return (string) \preg_replace("/[^A-Za-z]/", false, \implode("", \array_map("ucFirst", \explode($replace, \strtolower($this->slugify($string))))));
        }

        public function minify(string $content) : string {
            return (string) \preg_replace(["~\Q/*\E[\s\S]+?\Q*/\E~m", "~(?:http|ftp)s?://(*SKIP)(*FAIL)|//.+~m", "~^\s+|\R\s*~m"], false, $content);
        }              

        public function getSizeformat($size, int $precision = 2, $suffixes = ["B", "kB", "MB", "GB"]): string {
            $base = \log(\floatval($size)) / \log(1024);
            return (string) \round(\pow(1024, $base - \floor($base)), $precision) . $suffixes[\floor($base)];
        }

        public function getTimeformat(int $seconds, string $seperator = ":"): string {
            return (string) \sprintf("%02d%s%02d%s%02d", \floor($seconds / 3600), $seperator, ($seconds / 60 ) % 60, $seperator, $seconds % 60);
        }
        
        public function substring(string $string, int $start = 0, $length = 25, string $prefix = "", string $suffix = "", bool $fill = false, string $encoding = "UTF-8"): string {
            return (string) (\strlen($string) >= $length ? $prefix . \mb_substr($string, $start, $length, $encoding) . $suffix : ($fill ? \str_pad($string, $length + \strlen($suffix), " ", \STR_PAD_RIGHT) : $string));
        }

        public function sanitize($data): int|float|string|array|null {
            if (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->sanitize($value);
                }
            } elseif (\is_string($data)) {
                return (string) \htmlspecialchars(\strip_tags($data), \ENT_COMPAT | \ENT_HTML5);
            }

            return $data;
        }

        public function desanitize($data): int|float|string|array|null {
            if (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->desanitize($value);
                }
            } elseif (\is_string($data)) {
                return (string) \html_entity_decode($data, \ENT_QUOTES | \ENT_HTML5);
            }

            return $data;
        }

        public function getWords(string $content, int $min = 1, $max = 9999, array $keywords = [], int $count = 0): array {
            $words = \array_count_values(\str_word_count(\strip_tags(\nl2br($content)), 1));
            \asort($words);
            
            foreach (\array_keys(\array_reverse($words)) as $word) {
                if (!\in_array(($word = \trim($this->reName($word))), $keywords) && \strlen($word) >= $min && (\strlen($word) + $count) <= $max) {
                    $keywords[] = $word;
                    $count += \strlen($word);
                }
            }

            return (array) $keywords;
        }
        
        public function hasWords(string $haystack, array $needles) : bool {
            return (bool) \sizeof(\array_intersect($this->getWords($haystack), $needles));
        }
        
        public function hasInString(string $haystack, array $needles): bool {
            return (bool) \array_reduce($needles, fn($a, $n) => $a || \str_contains($haystack, $n), false);
        }
        
        public function matchInString(string $haystack, array $needles): bool{
            return (bool) \array_reduce($needles, fn($a, $n) => $a && \str_contains($haystack, $n), true);
        }        
        

        public function getSummary(string $content, int $min = 0, int $total = 99999, string $implode = ". ", string $eol = ".", array $sentences = [], int $count = 0): string {
            foreach (\array_unique(\preg_split('/(?<=[.?!])\s+(?=[a-z])/i', \strip_tags(\html_entity_decode($content)))) as $sentence) {
                if (\strlen($sentence) >= $min && (\strlen($sentence) + $count) <= $total && \sizeof($this->getWords($sentence))) {
                    $sentences[] = \trim($sentence, $eol);
                    $count += \strlen($sentence);
                }
            }

            return (string) \str_replace(["\"", "'", "`"], "", \implode($implode, \explode(\trim($implode), \implode(\trim($implode), \array_unique($sentences))))) . $eol;
        }
    }

}

