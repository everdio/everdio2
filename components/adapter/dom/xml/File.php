<?php
namespace Components\Adapter\Dom\Xml {
    final class File extends \Components\Adapter\Dom\Xml {  
        use \Components\Adapter\Dom\File;
        public function __dry() : string {
            return (string) sprintf("new \Components\Adapter\Dom\Xml\File((\"%s\"), \"%s\", \"%s\", LIBXML_HTML_NOIMPLIED | LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", $this->file, $this->xmlVersion, $this->xmlEncoding);            
        }
    }
}