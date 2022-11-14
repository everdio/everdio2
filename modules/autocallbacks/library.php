<?php
if ($this->request->sizeof(["library", "controller"])) {
    foreach ($this->library->restore() as $library => $namespace) {
        $this->library->store([$library => $this->getCallbacks($namespace)]);        
    }
    
    print_r($this->library->restore());
}