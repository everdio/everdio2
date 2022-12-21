<?php
if ($this->request->sizeof(["library", "controller"])) {
    foreach ($this->library->restore() as $library => $namespace) {
        $this->library->store([$library => $this->getCallbacks($namespace)]);        
    }
    
    $dir = new \Component\Caller\Dir($this->request->library);
    print_r($dir->recursive());
    
    print_r($this->library->restore());
}