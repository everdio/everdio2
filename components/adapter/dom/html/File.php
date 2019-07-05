<?php
namespace Components\Adapter\Dom\Html {
    final class File extends \Components\Adapter\Dom\Html {  
        use \Components\Adapter\Dom\File;
        public function __dry() : string {
            return (string) sprintf("new \Components\Adapter\Dom\Html\File(\"%s\", \"%s\", \"%s\", LIBXML_NOCDATA | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", $this->file, $this->xmlVersion, $this->xmlEncoding);
        }
    }
}