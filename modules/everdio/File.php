<?php
namespace Modules\Everdio {
    use \Modules\Everdio\Library\ECms;
    class File extends ECms\File {
        public function save() {
            $file = new \Components\File($this->File, "r");
            $this->Size = $file->getSize();
            $this->Basename = $file->getBasename("." . $file->getExtension());
            $this->Extension = $file->getExtension();        
            $this->Size = $file->getSize();
            parent::save();            
        }
        
        public function delete() {
            if (isset($this->File)) {
                try {
                    $file = new \Components\File($this->File, "r");
                    $file->delete();                         
                } catch (\RuntimeException $ex) {
                    new Event(sprintf("%s %s", $this->File, $ex->getMessage()));
                }
            }

            parent::delete();
        }
    }
}