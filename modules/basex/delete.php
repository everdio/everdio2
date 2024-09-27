<?php

if ($this instanceof Component\Core\Controller\Model\Cli) {
    
    $curl = new \Component\Caller\Curl;
    $curl->setopt_array([
        \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
        \CURLOPT_USERPWD => $this->request->username . ":" . $this->request->password,
        \CURLOPT_URL => $this->request->resource]);
    $curl->delete();
    $curl->execute();
}