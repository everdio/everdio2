<?php
namespace Component\Caller {
    class Curl extends \Component\Caller {
        public function __construct(string $caller = "curl_%s") {
            parent::__construct($caller);
            $this->handle = $this->init();
            $this->setopt(\CURLOPT_HTTPHEADER, ["Connection: keep-alive"]);
        }
        
        final public function get($handle) {
            $this->setopt_array([            
                \CURLOPT_FILE => $handle,
                \CURLOPT_BINARYTRANSFER => true]);    
        }
        
        final public function put($handle, int $size = 0) {
            $this->setopt_array([
                \CURLOPT_INFILE => $handle,
                \CURLOPT_INFILESIZE => $size,
                \CURLOPT_PUT => true,
                \CURLOPT_BINARYTRANSFER => true,
                \CURLOPT_RETURNTRANSFER => true]);    
            
            \fseek($handle, 0);
        }
        
        final public function post(string $content) {
            $this->setopt_array([
                \CURLOPT_POSTFIELDS => $content,
                \CURLOPT_INFILESIZE => \strlen($content),
                \CURLOPT_POST => true,
                \CURLOPT_RETURNTRANSFER => true]);            
        }

        public function execute() {
            if (($response = $this->exec()) === false) {
                throw new \RuntimeException($this->error());
            }
            
            return (string) \trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }
}